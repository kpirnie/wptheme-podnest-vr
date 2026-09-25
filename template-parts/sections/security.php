<?php
/**
 * Security
 * 
 * Static highlight cards for the account and platform security
 * 
 * @package PodNest Visual Regressor
 */

// We don't want to allow direct access to this
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// the cards: icon, title, description
$pnvr_security = [
    [ 'shield', __( 'Mandatory TOTP 2FA', 'pn-vr' ), __( 'Every account enrolls an authenticator, with twelve single-use recovery codes and optional remembered browsers.', 'pn-vr' ) ],
    [ 'key', __( 'Argon2id Passwords', 'pn-vr' ), __( 'Passwords are hashed with Argon2id, and sessions live server-side with a hard seven-day ceiling.', 'pn-vr' ) ],
    [ 'lock', __( 'Encrypted Secrets', 'pn-vr' ), __( 'Basic auth credentials, API keys, and the SMTP password are encrypted at rest, so a copy of the database is not a copy of your secrets.', 'pn-vr' ) ],
    [ 'link', __( 'Signed Screenshot Links', 'pn-vr' ), __( 'Screenshots are only served with a signed token that covers one run and lapses after an hour.', 'pn-vr' ) ],
    [ 'clock', __( 'Throttling & Lockout', 'pn-vr' ), __( 'Sign-in and 2FA attempts are limited per address and per account, and every form post carries a CSRF token.', 'pn-vr' ) ],
    [ 'globe', __( 'Private Network Guard', 'pn-vr' ), __( 'URLs that resolve to private, loopback, or reserved addresses are refused when they are saved, and again when they are captured.', 'pn-vr' ) ],
    [ 'users', __( 'Roles & Assignment', 'pn-vr' ), __( 'Admin, Manager, Trial, and User levels, with project assignment deciding who sees what.', 'pn-vr' ) ],
    [ 'list', __( 'Full Audit Log', 'pn-vr' ), __( 'Every change is recorded with who, when, and from where, plus the values before and after. Secrets are only noted as changed.', 'pn-vr' ) ],
];
?>
<section id="security" class="pnvr-section pnvr-section-alt" aria-labelledby="security-heading">
    <div class="container container-2xl">

        <header class="pnvr-section-header" data-scroll-reveal>
            <span class="pnvr-eyebrow"><?php esc_html_e( 'Security', 'pn-vr' ); ?></span>
            <h2 id="security-heading"><?php esc_html_e( 'It Points a Real Browser at the Web. It Is Built Like It.', 'pn-vr' ); ?></h2>
            <p class="pnvr-section-desc"><?php esc_html_e( 'Accounts, secrets, screenshots, and the network the browser can reach are all locked down by default.', 'pn-vr' ); ?></p>
        </header>

        <div class="pnvr-security-grid">
            <?php foreach( $pnvr_security as $pnvr_i => [ $pnvr_icon, $pnvr_title, $pnvr_desc ] ) : ?>
                <article class="pnvr-security-card" data-scroll-reveal data-scroll-delay="<?php echo esc_attr( ( $pnvr_i % 4 ) * 100 ); ?>">
                    <span class="pnvr-security-icon"><?php echo PNVR_Icons::svg( $pnvr_icon ); ?></span>
                    <h3><?php echo esc_html( $pnvr_title ); ?></h3>
                    <p><?php echo esc_html( $pnvr_desc ); ?></p>
                </article>
            <?php endforeach; ?>
        </div>

    </div>
</section>
