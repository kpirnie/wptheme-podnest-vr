<?php
/** 
 * 
 * Contact Class
 * 
 * The contact form: its submissions post type, the AJAX handler, the admin screens, and the plan subject pre-fill
 * 
 * Spam protection layers:
 *  - Nonce verification
 *  - Honeypot fields (website_url, company_name)
 *  - Time-based token check (under 3 seconds is a bot)
 *  - IP rate limiting via transient (3 an hour)
 *  - Content pattern matching (link markup, keywords, Cyrillic)
 *  - Optional reCAPTCHA v3 score threshold (0.5 and up)
 *  - Manual spam / not spam toggle in the admin list
 * 
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @copyright 2025 Kevin Pirnie
 * 
 * @since 1.0.1
 * @package PodNest Visual Regressor
 * 
*/

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// make sure we aren't loading in the class multiple times
if( ! class_exists( 'PNVR_Contact' ) ) {

    /** 
     * PNVR_Contact
     * 
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @copyright 2025 Kevin Pirnie
     * 
     * @since 1.0.1
     * @package PodNest Visual Regressor
     * @access public
     * 
    */
    class PNVR_Contact {

        /**
         * Submissions post type
         * @var string
         */
        public const CPT = 'pnvr_contact';

        /**
         * AJAX action and nonce action
         * @var string
         */
        public const ACTION = 'pnvr_submit_contact';
        public const NONCE = 'pnvr_contact_form';

        /**
         * Whether the form's config and scripts have been added to this page
         * @var bool
         */
        private static bool $config_added = false;

        /**
         * init
         * 
         * Hooks everything the contact form needs
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @return void Returns nothing
         */
        public static function init( ): void {

            // post type, status, and meta
            add_action( 'init', [ self::class, 'register_post_type' ] );

            // the form shortcode
            add_shortcode( 'pnvr_contact_form', [ self::class, 'shortcode' ] );

            // submissions
            add_action( 'wp_ajax_nopriv_' . self::ACTION, [ self::class, 'handle_submission' ] );
            add_action( 'wp_ajax_' . self::ACTION, [ self::class, 'handle_submission' ] );

            // admin screens
            add_action( 'admin_head', [ self::class, 'hide_add_new_button' ] );
            add_action( 'add_meta_boxes', [ self::class, 'add_meta_box' ] );
            add_action( 'admin_action_pnvr_spam_toggle', [ self::class, 'handle_spam_toggle' ] );
            add_action( 'restrict_manage_posts', [ self::class, 'spam_filter_dropdown' ] );
            add_filter( 'post_row_actions', [ self::class, 'spam_row_actions' ], 10, 2 );
            add_filter( 'manage_' . self::CPT . '_posts_columns', [ self::class, 'admin_columns' ] );
            add_action( 'manage_' . self::CPT . '_posts_custom_column', [ self::class, 'admin_column_content' ], 10, 2 );
        }

        /**
         * plan_subjects
         * 
         * The ?plan= values the form understands, and the subject each pre-fills
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @return array Returns plan => subject
         */
        public static function plan_subjects( ): array {

            // the known plans, filterable
            return apply_filters( 'pnvr_contact_plan_subjects', [
                'trial' => __( 'Visual Regressor - Trial request', 'pn-vr' ),
                'paid' => __( 'Visual Regressor - Paid plan', 'pn-vr' ),
                'source' => __( 'Visual Regressor - Source Code purchase', 'pn-vr' ),
                'signin' => __( 'Visual Regressor - Sign in', 'pn-vr' ),
                'support' => __( 'Visual Regressor - Support', 'pn-vr' ),
            ] );
        }

        /**
         * requested_plan
         * 
         * The plan from the query string, only when it is one the form knows
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @return string Returns the plan key, or an empty string
         */
        public static function requested_plan( ): string {

            // read and sanitize the query var
            $plan = isset( $_GET['plan'] ) ? sanitize_key( wp_unslash( $_GET['plan'] ) ) : '';

            // only a known plan
            return array_key_exists( $plan, self::plan_subjects( ) ) ? $plan : '';
        }

        /**
         * render_form
         * 
         * Renders the form, adding its config and optional reCAPTCHA script to the page
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @return string Returns the form markup
         */
        public static function render_form( ): string {

            // the config and scripts, once per page
            if( ! self::$config_added ) {
                self::$config_added = true;

                // the optional reCAPTCHA v3 library
                $site_key = pnvr_opt( 'recaptcha_site_key' );
                if( $site_key ) {
                    wp_enqueue_script( 'google-recaptcha', sprintf( 'https://www.google.com/recaptcha/api.js?render=%s', rawurlencode( $site_key ) ), [ ], null, true );
                }

                // the form config for the ContactForm component
                wp_add_inline_script(
                    PNVR_Assets::SCRIPT_HANDLE,
                    sprintf( 'window.pnvrContact=%s;', wp_json_encode( [
                        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                        'action' => self::ACTION,
                        'nonce' => wp_create_nonce( self::NONCE ),
                        'recaptchaKey' => $site_key,
                    ] ) ),
                    'before'
                );
            }

            // the plan and its subject
            $plan = self::requested_plan( );
            $subjects = self::plan_subjects( );

            // render the template
            ob_start( );
            get_template_part( 'template-parts/contact/form', null, [
                'plan' => $plan,
                'subject' => $plan ? $subjects[ $plan ] : '',
            ] );
            return (string) ob_get_clean( );
        }

        /**
         * shortcode
         * 
         * [pnvr_contact_form]
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @return string Returns the form markup
         */
        public static function shortcode( ): string {

            // return the form
            return self::render_form( );
        }

        /**
         * register_post_type
         * 
         * Registers the submissions post type, the spam status, and the submission meta
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @return void Returns nothing
         */
        public static function register_post_type( ): void {

            // the submissions
            register_post_type( self::CPT, [
                'labels' => [
                    'name' => __( 'Form Submissions', 'pn-vr' ),
                    'singular_name' => __( 'Submission', 'pn-vr' ),
                    'menu_name' => __( 'Form Items', 'pn-vr' ),
                    'edit_item' => __( 'View Submission', 'pn-vr' ),
                    'all_items' => __( 'All Submissions', 'pn-vr' ),
                ],
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_icon' => 'dashicons-email',
                'menu_position' => 27,
                'show_in_rest' => false,
                'capability_type' => 'post',
                'capabilities' => [ 'create_posts' => 'do_not_allow' ],
                'map_meta_cap' => true,
                'supports' => [ 'title' ],
                'rewrite' => false,
                'has_archive' => false,
                'publicly_queryable' => false,
                'exclude_from_search' => true,
            ] );

            // the spam status
            register_post_status( 'spam', [
                'label' => _x( 'Spam', 'post status', 'pn-vr' ),
                'public' => false,
                'exclude_from_search' => true,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                /* translators: %s: the spam count */
                'label_count' => _n_noop( 'Spam <span class="count">(%s)</span>', 'Spam <span class="count">(%s)</span>', 'pn-vr' ),
            ] );

            // the submission meta
            $auth = static fn( ) => current_user_can( 'edit_posts' );
            foreach( [ '_pnvr_first_name', '_pnvr_last_name', '_pnvr_sender_email', '_pnvr_phone', '_pnvr_plan', '_pnvr_subject', '_pnvr_message', '_pnvr_status', '_pnvr_ip', '_pnvr_user_agent' ] as $key ) {
                register_post_meta( self::CPT, $key, [
                    'show_in_rest' => false,
                    'single' => true,
                    'type' => 'string',
                    'auth_callback' => $auth,
                ] );
            }
        }

        /**
         * hide_add_new_button
         * 
         * Submissions only come from the form, so hide Add New
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @return void Returns nothing
         */
        public static function hide_add_new_button( ): void {

            // only our screen
            global $typenow;
            if( self::CPT === $typenow ) {
                echo '<style>.page-title-action{display:none!important}</style>';
            }
        }

        /**
         * add_meta_box
         * 
         * Registers the read-only submission details meta box
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @return void Returns nothing
         */
        public static function add_meta_box( ): void {

            // the meta box
            add_meta_box( 'pnvr-contact-view', __( 'Submission Details', 'pn-vr' ), [ self::class, 'render_meta_box' ], self::CPT, 'normal', 'high' );
        }

        /**
         * render_meta_box
         * 
         * Renders the submission, marking it read on first view
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @param WP_Post $post The submission
         * @return void Returns nothing
         */
        public static function render_meta_box( WP_Post $post ): void {

            // pull the meta
            $meta = static fn( string $key ): string => (string) get_post_meta( $post -> ID, $key, true );

            // mark it read
            if( 'read' !== $meta( '_pnvr_status' ) ) {
                update_post_meta( $post -> ID, '_pnvr_status', 'read' );
            }

            // the rows: label, value, how to show it
            $rows = [
                [ __( 'Name', 'pn-vr' ), trim( sprintf( '%s %s', $meta( '_pnvr_first_name' ), $meta( '_pnvr_last_name' ) ) ), 'text' ],
                [ __( 'Email', 'pn-vr' ), $meta( '_pnvr_sender_email' ), 'email' ],
                [ __( 'Phone', 'pn-vr' ), $meta( '_pnvr_phone' ), 'text' ],
                [ __( 'Plan', 'pn-vr' ), $meta( '_pnvr_plan' ), 'text' ],
                [ __( 'Subject', 'pn-vr' ), $meta( '_pnvr_subject' ), 'text' ],
                [ __( 'Message', 'pn-vr' ), $meta( '_pnvr_message' ), 'message' ],
                [ __( 'IP Address', 'pn-vr' ), $meta( '_pnvr_ip' ), 'text' ],
                [ __( 'User Agent', 'pn-vr' ), $meta( '_pnvr_user_agent' ), 'text' ],
                [ __( 'Received', 'pn-vr' ), (string) get_the_date( 'F j, Y \a\t g:i a', $post ), 'text' ],
            ];
            ?>
            <table class="form-table" role="presentation">
                <?php foreach( $rows as [ $label, $value, $type ] ) : ?>
                    <?php if( '' === $value ) { continue; } ?>
                    <tr>
                        <th scope="row"><?php echo esc_html( $label ); ?></th>
                        <td>
                            <?php if( 'email' === $type ) : ?>
                                <a href="<?php echo esc_url( sprintf( 'mailto:%s', $value ) ); ?>"><?php echo esc_html( $value ); ?></a>
                            <?php elseif( 'message' === $type ) : ?>
                                <pre style="white-space:pre-wrap;font-family:inherit;background:#f6f7f7;padding:12px;border-radius:4px;"><?php echo esc_html( $value ); ?></pre>
                            <?php else : ?>
                                <?php echo esc_html( $value ); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php
        }

        /**
         * admin_columns
         * 
         * The submissions list columns
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @param array $columns The default columns
         * @return array Returns the columns
         */
        public static function admin_columns( array $columns ): array {

            // return the columns
            return [
                'cb' => $columns['cb'] ?? '',
                'title' => __( 'Name', 'pn-vr' ),
                'pnvr_email' => __( 'Email', 'pn-vr' ),
                'pnvr_plan' => __( 'Plan', 'pn-vr' ),
                'pnvr_subject' => __( 'Subject', 'pn-vr' ),
                'pnvr_spam' => __( 'Spam', 'pn-vr' ),
                'pnvr_read' => __( 'Read', 'pn-vr' ),
                'date' => __( 'Date', 'pn-vr' ),
            ];
        }

        /**
         * admin_column_content
         * 
         * The submissions list column values
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @param string $column The column
         * @param int $post_id The submission ID
         * @return void Returns nothing
         */
        public static function admin_column_content( string $column, int $post_id ): void {

            // which column
            switch( $column ) {
                case 'pnvr_email':
                    $email = (string) get_post_meta( $post_id, '_pnvr_sender_email', true );
                    printf( '<a href="%1$s">%2$s</a>', esc_url( sprintf( 'mailto:%s', $email ) ), esc_html( $email ) );
                    break;
                case 'pnvr_plan':
                    echo esc_html( (string) get_post_meta( $post_id, '_pnvr_plan', true ) );
                    break;
                case 'pnvr_subject':
                    echo esc_html( (string) get_post_meta( $post_id, '_pnvr_subject', true ) );
                    break;
                case 'pnvr_spam':
                    $spam = 'spam' === get_post_status( $post_id );
                    printf(
                        '<span style="color:%1$s;font-weight:600;">%2$s</span>',
                        $spam ? '#dc3232' : '#46b450',
                        $spam ? esc_html__( 'Spam', 'pn-vr' ) : esc_html__( 'Clean', 'pn-vr' )
                    );
                    break;
                case 'pnvr_read':
                    $status = (string) ( get_post_meta( $post_id, '_pnvr_status', true ) ?: 'new' );
                    printf(
                        '<span style="color:%1$s;font-weight:600;text-transform:capitalize;">%2$s</span>',
                        'new' === $status ? '#1fd46e' : '#6b8cae',
                        esc_html( $status )
                    );
                    break;
            }
        }

        /**
         * spam_row_actions
         * 
         * Adds Mark as Spam / Not Spam to the submissions list rows
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @param array $actions The row actions
         * @param WP_Post $post The submission
         * @return array Returns the row actions
         */
        public static function spam_row_actions( array $actions, WP_Post $post ): array {

            // only our post type
            if( self::CPT !== $post -> post_type ) {
                return $actions;
            }

            // which way the toggle goes
            $is_spam = 'spam' === get_post_status( $post -> ID );
            $url = add_query_arg( [
                'action' => 'pnvr_spam_toggle',
                'post_id' => $post -> ID,
                'mark' => $is_spam ? 'clean' : 'spam',
                '_wpnonce' => wp_create_nonce( sprintf( 'pnvr_spam_toggle_%d', $post -> ID ) ),
            ], admin_url( 'admin.php' ) );

            // add the action
            $actions[ $is_spam ? 'not_spam' : 'mark_spam' ] = sprintf(
                '<a href="%1$s" style="color:%2$s;">%3$s</a>',
                esc_url( $url ),
                $is_spam ? '#46b450' : '#dc3232',
                $is_spam ? esc_html__( 'Not Spam', 'pn-vr' ) : esc_html__( 'Mark as Spam', 'pn-vr' )
            );

            // return the actions
            return $actions;
        }

        /**
         * spam_filter_dropdown
         * 
         * The clean / spam filter on the submissions list
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @return void Returns nothing
         */
        public static function spam_filter_dropdown( ): void {

            // only our screen
            global $typenow;
            if( self::CPT !== $typenow ) {
                return;
            }

            // the current filter
            $current = isset( $_GET['post_status'] ) ? sanitize_key( wp_unslash( $_GET['post_status'] ) ) : '';
            ?>
            <select name="post_status">
                <option value=""><?php esc_html_e( 'All Statuses', 'pn-vr' ); ?></option>
                <option value="publish" <?php selected( $current, 'publish' ); ?>><?php esc_html_e( 'Clean', 'pn-vr' ); ?></option>
                <option value="spam" <?php selected( $current, 'spam' ); ?>><?php esc_html_e( 'Spam', 'pn-vr' ); ?></option>
            </select>
            <?php
        }

        /**
         * handle_spam_toggle
         * 
         * The spam toggle admin action
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @return void Returns nothing
         */
        public static function handle_spam_toggle( ): void {

            // the request
            $post_id = isset( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;
            $mark = isset( $_GET['mark'] ) ? sanitize_key( wp_unslash( $_GET['mark'] ) ) : '';
            $nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

            // check the nonce
            if( ! wp_verify_nonce( $nonce, sprintf( 'pnvr_spam_toggle_%d', $post_id ) ) ) {
                wp_die( esc_html__( 'Security check failed', 'pn-vr' ) );
            }

            // check the user can edit this submission
            if( ! $post_id || self::CPT !== get_post_type( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
                wp_die( esc_html__( 'Unauthorized', 'pn-vr' ) );
            }

            // flip it
            wp_update_post( [
                'ID' => $post_id,
                'post_status' => 'spam' === $mark ? 'spam' : 'publish',
            ] );

            // back to the list
            wp_safe_redirect( admin_url( sprintf( 'edit.php?post_type=%s', self::CPT ) ) );
            exit;
        }

        /**
         * handle_submission
         * 
         * The AJAX submission, with the protection layers applied in order
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @return void Returns nothing, it sends JSON and exits
         */
        public static function handle_submission( ): void {

            // the message a caught bot also sees, so detection is not revealed
            $thanks = __( 'Thank you! Your message has been sent. We\'ll be in touch soon.', 'pn-vr' );

            // 1. nonce
            if( ! check_ajax_referer( self::NONCE, 'nonce', false ) ) {
                wp_send_json_error( [ 'message' => __( 'Security check failed. Please refresh and try again.', 'pn-vr' ) ] );
            }

            // 2. honeypots
            if( ! empty( $_POST['website_url'] ) || ! empty( $_POST['company_name'] ) ) {
                wp_send_json_success( [ 'message' => $thanks ] );
            }

            // 3. time-based token, under 3 seconds is a bot
            $form_time = (int) base64_decode( sanitize_text_field( wp_unslash( $_POST['form_token'] ?? '' ) ) );
            if( $form_time && ( time( ) - $form_time ) < 3 ) {
                wp_send_json_success( [ 'message' => $thanks ] );
            }

            // 4. IP rate limiting, 3 an hour
            $ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) );
            $rate_key = sprintf( 'pnvr_contact_limit_%s', md5( $ip ) );
            $hits = (int) get_transient( $rate_key );
            if( $hits >= 3 ) {
                wp_send_json_error( [ 'message' => __( 'Too many submissions. Please try again later.', 'pn-vr' ) ] );
            }
            set_transient( $rate_key, $hits + 1, HOUR_IN_SECONDS );

            // 5. sanitize
            $first = sanitize_text_field( wp_unslash( $_POST['first_name'] ?? '' ) );
            $last = sanitize_text_field( wp_unslash( $_POST['last_name'] ?? '' ) );
            $email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
            $phone = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
            $subject = sanitize_text_field( wp_unslash( $_POST['subject'] ?? '' ) );
            $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
            $plan = sanitize_key( wp_unslash( $_POST['plan'] ?? '' ) );
            $plan = array_key_exists( $plan, self::plan_subjects( ) ) ? $plan : '';

            // 6. required fields
            if( empty( $first ) || empty( $last ) || empty( $email ) || empty( $message ) ) {
                wp_send_json_error( [ 'message' => __( 'Please fill in all required fields.', 'pn-vr' ) ] );
            }
            if( ! is_email( $email ) ) {
                wp_send_json_error( [ 'message' => __( 'Please enter a valid email address.', 'pn-vr' ) ] );
            }

            // 7. spam content patterns
            $combined = sprintf( '%s %s %s', $first, $last, $message );
            $patterns = [
                '/\[url=/i',
                '/\[link=/i',
                '/<a\s+href/i',
                '/https?:\/\/[^\s]+\s+https?:\/\//i',
                '/viagra|cialis|casino|lottery|cryptocurrency|bitcoin|crypto wallet/i',
                '/click here.*https?:\/\//i',
                '/dear\s+(sir|madam|friend)/i',
                '/\b(SEO|backlink|rank|traffic)\b.*\b(service|offer|guarantee)/i',
            ];
            foreach( $patterns as $pattern ) {
                if( preg_match( $pattern, $combined ) ) {
                    wp_send_json_success( [ 'message' => $thanks ] );
                }
            }

            // 8. excessive links
            if( preg_match_all( '/https?:\/\//i', $message ) > 2 ) {
                wp_send_json_success( [ 'message' => $thanks ] );
            }

            // 9. Cyrillic content
            if( preg_match( '/[\x{0400}-\x{04FF}]/u', $combined ) ) {
                wp_send_json_success( [ 'message' => $thanks ] );
            }

            // 10. optional reCAPTCHA v3
            $secret = pnvr_opt( 'recaptcha_secret_key' );
            if( $secret ) {
                $token = sanitize_text_field( wp_unslash( $_POST['recaptcha_token'] ?? '' ) );
                if( ! self::verify_recaptcha( $token, $secret ) ) {
                    wp_send_json_error( [ 'message' => __( 'Verification failed. Please try again.', 'pn-vr' ) ] );
                }
            }

            // store it
            $post_id = wp_insert_post( [
                'post_type' => self::CPT,
                'post_title' => sprintf( '%s %s', $first, $last ),
                'post_status' => 'publish',
            ], true );
            if( is_wp_error( $post_id ) ) {
                wp_send_json_error( [ 'message' => __( 'Could not save your message. Please try again.', 'pn-vr' ) ] );
            }

            // the meta
            $subject = $subject ?: __( 'Contact Form Submission', 'pn-vr' );
            $meta = [
                '_pnvr_first_name' => $first,
                '_pnvr_last_name' => $last,
                '_pnvr_sender_email' => $email,
                '_pnvr_phone' => $phone,
                '_pnvr_plan' => $plan,
                '_pnvr_subject' => $subject,
                '_pnvr_message' => $message,
                '_pnvr_status' => 'new',
                '_pnvr_ip' => $ip,
                '_pnvr_user_agent' => sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ?? '' ) ),
            ];
            foreach( $meta as $key => $value ) {
                update_post_meta( $post_id, $key, $value );
            }

            // let the admin know
            self::send_notification( $meta, $post_id );

            // done
            wp_send_json_success( [ 'message' => $thanks ] );
        }

        /**
         * verify_recaptcha
         * 
         * Verifies a reCAPTCHA v3 token, failing anything scored under 0.5
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @param string $token The token from grecaptcha.execute()
         * @param string $secret The secret key
         * @return bool Returns whether it passed
         */
        private static function verify_recaptcha( string $token, string $secret ): bool {

            // no token, no pass
            if( empty( $token ) ) {
                return false;
            }

            // ask google
            $response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', [
                'body' => [ 'secret' => $secret, 'response' => $token ],
                'timeout' => 10,
            ] );
            if( is_wp_error( $response ) ) {
                return false;
            }

            // check the score
            $body = json_decode( wp_remote_retrieve_body( $response ), true );
            return ! empty( $body['success'] ) && ( (float) ( $body['score'] ?? 0 ) ) >= 0.5;
        }

        /**
         * send_notification
         * 
         * Emails the admin a plain-text copy of the submission
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @param array $meta The submission meta
         * @param int $post_id The submission ID
         * @return void Returns nothing
         */
        private static function send_notification( array $meta, int $post_id ): void {

            // the name, used twice
            $name = sprintf( '%s %s', $meta['_pnvr_first_name'], $meta['_pnvr_last_name'] );

            // the body
            $body = sprintf( "Name:    %s\nEmail:   %s\n", $name, $meta['_pnvr_sender_email'] );
            if( $meta['_pnvr_phone'] ) {
                $body .= sprintf( "Phone:   %s\n", $meta['_pnvr_phone'] );
            }
            if( $meta['_pnvr_plan'] ) {
                $body .= sprintf( "Plan:    %s\n", $meta['_pnvr_plan'] );
            }
            $body .= sprintf(
                "Subject: %1\$s\n\nMessage:\n%2\$s\n\n---\nView submission: %3\$s",
                $meta['_pnvr_subject'],
                $meta['_pnvr_message'],
                admin_url( sprintf( 'post.php?post=%d&action=edit', $post_id ) )
            );

            // send it
            wp_mail(
                get_option( 'admin_email' ),
                sprintf( '[%1$s] %2$s', get_bloginfo( 'name' ), $meta['_pnvr_subject'] ),
                $body,
                [
                    sprintf( 'Reply-To: %1$s <%2$s>', $name, $meta['_pnvr_sender_email'] ),
                    'Content-Type: text/plain; charset=UTF-8',
                ]
            );
        }

    }

}
