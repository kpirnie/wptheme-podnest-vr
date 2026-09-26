<?php
/**
 * Contact form
 * 
 * Rendered by PNVR_Contact::render_form(), submitted by the ContactForm component to PNVR_Contact::handle_submission()
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// the plan and the subject it pre-fills
$pnvr_plan = $args['plan'] ?? '';
$pnvr_subject = $args['subject'] ?? '';
?>
<div class="pnvr-contact" data-contact-form>

    <div class="pnvr-contact-success" aria-live="polite" hidden>
        <span class="pnvr-contact-success-icon" aria-hidden="true">&#10003;</span>
        <p class="pnvr-contact-success-msg"></p>
    </div>

    <form class="pnvr-contact-form" novalidate aria-label="<?php esc_attr_e( 'Contact form', 'pn-vr' ); ?>">

        <!-- honeypots -->
        <div class="pnvr-contact-hp" aria-hidden="true">
            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
            <input type="text" name="company_name" tabindex="-1" autocomplete="off">
        </div>
        <input type="hidden" name="form_token" value="<?php echo esc_attr( base64_encode( (string) time( ) ) ); ?>">
        <input type="hidden" name="plan" value="<?php echo esc_attr( $pnvr_plan ); ?>">

        <div class="pnvr-form-row">
            <div class="pnvr-form-group">
                <label for="pnvr-first-name"><?php esc_html_e( 'First Name', 'pn-vr' ); ?> <span class="pnvr-required" aria-hidden="true">*</span></label>
                <input type="text" id="pnvr-first-name" name="first_name" required autocomplete="given-name" placeholder="<?php esc_attr_e( 'Jane', 'pn-vr' ); ?>">
            </div>
            <div class="pnvr-form-group">
                <label for="pnvr-last-name"><?php esc_html_e( 'Last Name', 'pn-vr' ); ?> <span class="pnvr-required" aria-hidden="true">*</span></label>
                <input type="text" id="pnvr-last-name" name="last_name" required autocomplete="family-name" placeholder="<?php esc_attr_e( 'Smith', 'pn-vr' ); ?>">
            </div>
        </div>

        <div class="pnvr-form-row">
            <div class="pnvr-form-group">
                <label for="pnvr-email"><?php esc_html_e( 'Email Address', 'pn-vr' ); ?> <span class="pnvr-required" aria-hidden="true">*</span></label>
                <input type="email" id="pnvr-email" name="email" required autocomplete="email" placeholder="<?php esc_attr_e( 'jane@example.com', 'pn-vr' ); ?>">
            </div>
            <div class="pnvr-form-group">
                <label for="pnvr-phone"><?php esc_html_e( 'Phone', 'pn-vr' ); ?></label>
                <input type="tel" id="pnvr-phone" name="phone" autocomplete="tel" placeholder="<?php esc_attr_e( '+1 (555) 000-0000', 'pn-vr' ); ?>">
            </div>
        </div>

        <div class="pnvr-form-group">
            <label for="pnvr-subject"><?php esc_html_e( 'Subject', 'pn-vr' ); ?></label>
            <input type="text" id="pnvr-subject" name="subject" value="<?php echo esc_attr( $pnvr_subject ); ?>" placeholder="<?php esc_attr_e( 'How can we help?', 'pn-vr' ); ?>">
        </div>

        <div class="pnvr-form-group">
            <label for="pnvr-message"><?php esc_html_e( 'Message', 'pn-vr' ); ?> <span class="pnvr-required" aria-hidden="true">*</span></label>
            <textarea id="pnvr-message" name="message" rows="7" required placeholder="<?php esc_attr_e( 'Tell us about your sites, or your question...', 'pn-vr' ); ?>"></textarea>
        </div>

        <div class="pnvr-contact-error" role="alert" hidden></div>

        <div class="pnvr-contact-actions">
            <button type="submit" class="pnvr-btn-primary">
                <span class="pnvr-contact-label"><?php esc_html_e( 'Send Message', 'pn-vr' ); ?></span>
                <span class="pnvr-contact-spinner" aria-hidden="true" hidden></span>
            </button>
            <?php if( pnvr_opt( 'recaptcha_site_key' ) ) : ?>
                <p class="pnvr-contact-note"><?php esc_html_e( 'Protected by reCAPTCHA.', 'pn-vr' ); ?></p>
            <?php endif; ?>
        </div>

    </form>
</div>
