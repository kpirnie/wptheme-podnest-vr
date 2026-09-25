<?php
/** 
 * 
 * SEO Class
 * 
 * JSON-LD structured data for Visual Regressor: a SoftwareApplication with its paid offers and the
 * Organization on the front page, and a WebPage on every other page
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
if( ! class_exists( 'PNVR_SEO' ) ) {

    /** 
     * PNVR_SEO
     * 
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @copyright 2025 Kevin Pirnie
     * 
     * @since 1.0.1
     * @package PodNest Visual Regressor
     * @access public
     * 
    */
    class PNVR_SEO {

        /**
         * init
         * 
         * Hooks the structured data and the Yoast integration
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

            // structured data
            add_action( 'wp_head', [ self::class, 'output_structured_data' ], 20 );

            // fall back to the page excerpt when Yoast has no meta description
            add_filter( 'wpseo_metadesc', [ self::class, 'yoast_metadesc_fallback' ] );

            // the schema is owned by the theme, so drop Yoast's graph rather than ship duplicate nodes
            add_filter( 'wpseo_json_ld_output', '__return_empty_array' );
        }

        /**
         * output_structured_data
         * 
         * Outputs the JSON-LD for the current page
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
        public static function output_structured_data( ): void {

            // the front page: the product and who makes it
            if( is_front_page( ) ) {
                self::output_schema( self::software_application_schema( ) );
                self::output_schema( self::organization_schema( ) );
                return;
            }

            // any other page
            if( is_page( ) ) {
                self::output_schema( self::page_schema( ) );
            }
        }

        /**
         * yoast_metadesc_fallback
         * 
         * Supplies the page excerpt as the Yoast meta description when Yoast has none
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access public
         * @static
         * 
         * @param string $desc The description Yoast resolved
         * @return string Returns the description
         */
        public static function yoast_metadesc_fallback( string $desc ): string {

            // only an empty description on a page with an excerpt
            if( '' === trim( $desc ) && is_singular( 'page' ) && has_excerpt( ) ) {
                return mb_strimwidth( wp_strip_all_tags( get_the_excerpt( ) ), 0, 160, '...' );
            }

            // return what yoast had
            return $desc;
        }

        /**
         * software_application_schema
         * 
         * The SoftwareApplication, with the pricing tiers as its offers
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @return array Returns the schema
         */
        private static function software_application_schema( ): array {

            // the schema
            $schema = [
                '@context' => 'https://schema.org',
                '@type' => 'SoftwareApplication',
                'name' => 'PodNest Visual Regressor',
                'alternateName' => 'Visual Regressor',
                'applicationCategory' => 'DeveloperApplication',
                'applicationSubCategory' => 'Visual regression testing',
                'operatingSystem' => 'Web browser; Linux (container)',
                'url' => home_url( '/' ),
                'image' => pnvr_logo_url( 'svg' ),
                'description' => 'Screenshot two URLs, compare them pixel for pixel, check the content you care about is on both, and let an AI judge whether what changed is a real regression or just a rotating advert.',
                'author' => self::person_schema( ),
                'publisher' => self::publisher_schema( ),
                'copyrightHolder' => self::person_schema( ),
                'copyrightYear' => '2026',
                'featureList' => self::feature_list( ),
                'runtimePlatform' => 'Python; FastAPI; Playwright; Chromium; MariaDB',
                'softwareRequirements' => 'A modern web browser. Self-hosting: Podman or Docker on Linux, amd64 or arm64',
                'keywords' => 'visual regression testing, screenshot comparison, pixel diff, content checks, AI visual review, website QA, broken link checker, PDF reports',
            ];

            // the hero candidate image doubles as the screenshot
            $screenshot = absint( get_theme_mod( PNVR_Customizer::PREFIX . 'hero_image_after', 0 ) );
            if( $screenshot && wp_attachment_is_image( $screenshot ) ) {
                $schema['screenshot'] = wp_get_attachment_image_url( $screenshot, 'full' );
            }

            // the license page, commercial rather than open source
            $license = absint( get_theme_mod( PNVR_Customizer::PREFIX . 'license_page', 0 ) );
            if( $license && get_permalink( $license ) ) {
                $schema['license'] = get_permalink( $license );
            }

            // the support center
            $schema['softwareHelp'] = [ '@type' => 'CreativeWork', 'url' => pnvr_opt( 'support_url', 'https://support.podnest.us/' ) ];

            // the offers
            $offers = self::offers( );
            if( $offers ) {
                $schema['offers'] = $offers;
            }

            // return the schema, filterable
            return apply_filters( 'pnvr_software_application_schema', $schema );
        }

        /**
         * offers
         * 
         * An Offer for each pricing tier, with an extra annual Offer where the tier has one
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @return array Returns the offers
         */
        private static function offers( ): array {

            // hold the offers
            $offers = [ ];

            // loop the tiers
            foreach( PNVR_Post_Types::get_posts( PNVR_Post_Types::PRICING ) as $tier ) {

                // the tier's values
                $meta = static fn( string $key ): string => trim( (string) get_post_meta( $tier -> ID, $key, true ) );
                $name = wp_strip_all_tags( get_the_title( $tier ) );
                $url = pnvr_contact_url( $meta( '_pnvr_cta_plan' ) );

                // no price means custom pricing, which has no offer to describe
                if( '' === $meta( '_pnvr_price' ) || ! is_numeric( $meta( '_pnvr_price' ) ) ) {
                    continue;
                }

                // the main price
                $offers[] = self::offer( $name, $meta( '_pnvr_price' ), $meta( '_pnvr_price_unit' ), $url );

                // the annual price
                if( '' !== $meta( '_pnvr_price_annual' ) && is_numeric( $meta( '_pnvr_price_annual' ) ) ) {
                    $offers[] = self::offer( sprintf( '%s (annual)', $name ), $meta( '_pnvr_price_annual' ), $meta( '_pnvr_price_annual_unit' ) ?: '/ year', $url );
                }
            }

            // return them
            return $offers;
        }

        /**
         * offer
         * 
         * A single Offer, with a billing period when the unit reads as monthly or yearly
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @param string $name The offer name
         * @param string $price The price
         * @param string $unit The price unit, e.g. / month
         * @param string $url Where to take it up
         * @return array Returns the offer
         */
        private static function offer( string $name, string $price, string $unit, string $url ): array {

            // the offer
            $offer = [
                '@type' => 'Offer',
                'name' => $name,
                'price' => number_format( (float) $price, 2, '.', '' ),
                'priceCurrency' => 'USD',
                'availability' => 'https://schema.org/InStock',
                'url' => $url,
            ];

            // a recurring price
            $unit = strtolower( $unit );
            $period = match( true ) {
                str_contains( $unit, 'month' ) || str_contains( $unit, '/mo' ) => [ 'P1M', 'MON' ],
                str_contains( $unit, 'year' ) || str_contains( $unit, 'annual' ) || str_contains( $unit, '/yr' ) => [ 'P1Y', 'ANN' ],
                default => null,
            };
            if( $period ) {
                $offer['priceSpecification'] = [
                    '@type' => 'UnitPriceSpecification',
                    'price' => $offer['price'],
                    'priceCurrency' => 'USD',
                    'billingDuration' => $period[0],
                    'unitCode' => $period[1],
                ];
            }

            // return it
            return $offer;
        }

        /**
         * organization_schema
         * 
         * The Organization
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @return array Returns the schema
         */
        private static function organization_schema( ): array {

            // return the schema
            return [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => 'Kevin Pirnie',
                'slogan' => 'Expert WordPress, Development, & DevOps Solutions',
                'url' => home_url( '/' ),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => pnvr_logo_url( 'svg' ),
                ],
                'founder' => self::toned_down_person_schema( ),
                'address' => 'Feeding Hills, MA 01030 - United States',
                'email' => 'info@podne.st',
                'telephone' => '+1-405-757-4678 (757-HOST)',
                'sameAs' => [
                    'https://github.com/kpirnie',
                    'https://kevinpirnie.com/',
                ],
            ];
        }

        /**
         * page_schema
         * 
         * A WebPage for a standard page
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @return array Returns the schema
         */
        private static function page_schema( ): array {

            // the image, falling back to the logo
            $image = has_post_thumbnail( ) ? get_the_post_thumbnail_url( null, 'full' ) : pnvr_logo_url( 'png' );

            // return the schema
            return [
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => wp_strip_all_tags( get_the_title( ) ),
                'description' => wp_trim_words( wp_strip_all_tags( has_excerpt( ) ? get_the_excerpt( ) : get_the_content( ) ), 30, '...' ),
                'url' => get_permalink( ),
                'datePublished' => get_the_date( 'c' ),
                'dateModified' => get_the_modified_date( 'c' ),
                'publisher' => self::publisher_schema( ),
                'image' => [ '@type' => 'ImageObject', 'url' => $image ],
                'isPartOf' => [ '@type' => 'WebSite', 'name' => get_bloginfo( 'name' ), 'url' => home_url( '/' ) ],
            ];
        }

        /**
         * person_schema
         * 
         * Kevin, as a Person
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @return array Returns the schema fragment
         */
        private static function person_schema( ): array {

            // return the person
            return [
                '@type' => 'Person',
                'name' => 'Kevin Pirnie',
                'givenName' => 'Kevin',
                'familyName' => 'Pirnie',
                'address' => 'Feeding Hills, MA 01030 - United States',
                'email' => 'iam@kevinpirnie.com',
                'telephone' => '+1-405-757-4678 (757-HOST)',
                'jobTitle' => 'DevOps Support Lead, WordPress/Hosting',
                'url' => 'https://kevinpirnie.com/',
                'sameAs' => [
                    'https://github.com/kpirnie',
                    'https://kevinpirnie.com/',
                ],
            ];
        }

        /**
         * toned_down_person_schema
         * 
         * The Person without the contact details
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @return array Returns the schema fragment
         */
        private static function toned_down_person_schema( ): array {

            // drop the contact details
            $person = self::person_schema( );
            unset( $person['address'], $person['email'], $person['telephone'] );

            // return it
            return $person;
        }

        /**
         * publisher_schema
         * 
         * The Organization as a nested publisher fragment
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @return array Returns the schema fragment
         */
        private static function publisher_schema( ): array {

            // the organization without its context
            $org = self::organization_schema( );
            unset( $org['@context'] );

            // return it
            return $org;
        }

        /**
         * feature_list
         * 
         * The featureList from the published Features, with a fallback so it is never empty
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @return string Returns the list
         */
        private static function feature_list( ): string {

            // the feature titles
            $names = array_map(
                static fn( WP_Post $post ): string => strtolower( wp_strip_all_tags( get_the_title( $post ) ) ),
                PNVR_Post_Types::get_posts( PNVR_Post_Types::FEATURE )
            );

            // the fallback
            if( empty( $names ) ) {
                return 'a vs b screenshot comparison, pixel diff, expected content checks, ai verdicts, same-host spider, pdf run reports, emailed reports, manual pass with note, per-url basic auth, mandatory totp 2fa, audit log';
            }

            // return the list
            return implode( ', ', $names );
        }

        /**
         * output_schema
         * 
         * Echoes a schema as a JSON-LD script tag
         * 
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * @copyright 2025 Kevin Pirnie
         * 
         * @since 1.0.1
         * @package PodNest Visual Regressor
         * @access private
         * @static
         * 
         * @param array $schema The schema
         * @return void Returns nothing
         */
        private static function output_schema( array $schema ): void {

            // wp_json_encode escapes the slashes in any closing script tag
            printf(
                '%1$s<script type="application/ld+json">%2$s</script>%1$s',
                PHP_EOL,
                wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG )
            );
        }

    }

}
