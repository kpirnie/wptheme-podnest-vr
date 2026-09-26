# PodNest Visual Regressor

[![Last Commit](https://img.shields.io/github/last-commit/kpirnie/wptheme-podnest-vr?style=for-the-badge&labelColor=000&logoColor=white&logo=data:image/svg%2Bxml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIxLjgiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PHJlY3QgeD0iMyIgeT0iNC41IiB3aWR0aD0iMTgiIGhlaWdodD0iMTYuNSIgcng9IjIiLz48bGluZSB4MT0iMyIgeTE9IjkuNSIgeDI9IjIxIiB5Mj0iOS41Ii8+PGxpbmUgeDE9IjgiIHkxPSIyLjUiIHgyPSI4IiB5Mj0iNi41Ii8+PGxpbmUgeDE9IjE2IiB5MT0iMi41IiB4Mj0iMTYiIHkyPSI2LjUiLz48L3N2Zz4=)](https://github.com/kpirnie/wptheme-podnest-vr/commits/main)
[![License: MIT](https://img.shields.io/badge/License-MIT-orange.svg?style=for-the-badge&logo=opensourceinitiative&logoColor=white&labelColor=000)](LICENSE)
[![WordPress](https://img.shields.io/badge/Min.%20WP-7.1.2-3858e9?logo=wordpress&logoColor=white&style=for-the-badge&labelColor=000)](https://wordpress.org)
[![Kevin Pirnie](https://img.shields.io/badge/-KevinPirnie.com-000d2d?style=for-the-badge&labelColor=000&logoColor=white&logo=data:image/svg%2Bxml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIxLjgiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+CiAgPGNpcmNsZSBjeD0iMTIiIGN5PSIxMiIgcj0iMTAiLz4KICA8ZWxsaXBzZSBjeD0iMTIiIGN5PSIxMiIgcng9IjQuNSIgcnk9IjEwIi8+CiAgPGxpbmUgeDE9IjIiIHkxPSIxMiIgeDI9IjIyIiB5Mj0iMTIiLz4KICA8bGluZSB4MT0iNC41IiB5MT0iNi41IiB4Mj0iMTkuNSIgeTI9IjYuNSIvPgogIDxsaW5lIHgxPSI0LjUiIHkxPSIxNy41IiB4Mj0iMTkuNSIgeTI9IjE3LjUiLz4KPC9zdmc+Cg==)](https://kevinpirnie.com/)

WordPress marketing theme for **PodNest Visual Regressor**: screenshot and content comparison with an AI judge. It is a standalone theme, not a child theme, built on the KP theme framework with Tailwind CSS 4, and styled as a green-accented sibling of the PodNest theme.

## Requirements

- WordPress 6.4+
- PHP 8.2+
- Node.js 26.10+ and npm 12.1+ (build only)
- Composer (autoloader only)

## Installation

1. Upload the theme folder as `wp-content/themes/pn-vr`.
2. Activate it under **Appearance > Themes**.
3. Set a static homepage under **Settings > Reading**. The front page template renders every section, so the page's own content is not used.
4. Create a page with the **Support** template and pick it under **Customizer > Contact > Contact page**. Every call to action links there.

## Build

```bash
npm install
npm run build
```

| Output | Source |
|---|---|
| `assets/css/theme.css` | `src/css/main.css`, compiled and minified by Tailwind CSS 4 |
| `assets/js/theme.js` | `src/js/main.js`, bundled and minified by esbuild |

```bash
npm run start       # watch both
npm run build:css   # CSS only
npm run build:js    # JS only
```

The built files are committed, because the deploy workflow ships the repository as it is. When `assets/js/theme.js` is missing, the theme loads `src/js/main.js` as an ES module instead.

`refresh.sh` rebuilds everything on a server: the Composer autoloader, the npm build, and the `languages/pn-vr.pot` translation template.

## Structure

| Path | What it holds |
|---|---|
| `work/pnvr-main.php` | `PNVR_Main`: constants and every hook |
| `work/inc/` | The classes, autoloaded by Composer's classmap, plus `pnvr-helpers.php` |
| `template-parts/` | Brand wordmark, navigation, hero, front-page sections, contact form, illustrations |
| `page-templates/support.php` | The Support page template |
| `src/css/tokens.css` | Design tokens: navy surfaces, neon green accent, fonts, radii |
| `src/css/components/` | Framework components (buttons, forms, tables, and more), in Tailwind's base and components layers |
| `src/css/site/` | Site styles: header, footer, hero, sections, pricing, contact |
| `src/js/components/` | Framework components, plus the compare slider and the contact form |

Colors, fonts, and radii are Tailwind theme tokens, so they are available as utilities, like `bg-pn-card`, `text-pn-accent`, `rounded-pn`, and `font-brand`, and as CSS variables, like `var(--color-pn-accent)`.

## Content Management

The front page is fixed in `front-page.php`: hero, marquee, features, how it works, AI judge, security, pricing, and the call to action. What changes lives in **VR Content** and the Customizer.

| Section | Where |
|---|---|
| Marquee strip | VR Content > Marquee (title only). Falls back to a built-in list. |
| Features grid | VR Content > Features: title, excerpt, and an icon |
| Pricing cards | VR Content > Pricing: price, unit, optional annual price, features list, featured flag, button plan |
| Hero copy, slider images | Customizer > Hero Section |
| Contact page, support URL, reCAPTCHA | Customizer > Contact |
| License page | Customizer > Structured Data |

Order posts with the **Order** field. In a pricing features list, a line starting with `x:` shows as not included. When any tier has an annual price, the pricing section shows a monthly / annual switch.

Ready-to-paste copy for every post is in [docs/cpt-content.md](docs/cpt-content.md), and the Source Code license draft is in [docs/license-draft.md](docs/license-draft.md).

### Hero slider

The hero's before/after slider uses the two images from **Customizer > Hero Section**. Until they are set, it shows illustrated placeholder pages with the differences outlined.

### Navigation

Assign menus to the **Primary Menu** and **Footer Menu** locations. Give a primary menu item the CSS class `nav-cta` to render it as the green button. The CSS Classes field is under **Screen Options** on the Menus screen.

### Contact form

The form is on the Support template, and on any page with the `[pnvr_contact_form]` shortcode. Submissions are stored under **Form Items** and emailed to the site admin address.

A `?plan=` value on the contact page URL pre-fills the subject:

| Plan | Subject |
|---|---|
| `trial` | Visual Regressor - Trial request |
| `paid` | Visual Regressor - Paid plan |
| `source` | Visual Regressor - Source Code purchase |
| `signin` | Visual Regressor - Sign in |
| `support` | Visual Regressor - Support |

Any other value is ignored. The list can be changed with the `pnvr_contact_plan_subjects` filter.

Spam protection: nonce, honeypot fields, a minimum time on the form, 3 submissions an hour per IP, content pattern checks, optional reCAPTCHA v3, and a manual spam toggle in the admin list.

### Structured data

The front page outputs a `SoftwareApplication`, with an offer for each pricing tier, and an `Organization`. Other pages output a `WebPage`. Yoast's own schema output is turned off so nodes are not duplicated. The application schema can be changed with the `pnvr_software_application_schema` filter.

### Theme settings

**PN-VR Settings** in wp-admin holds the framework's settings tabs.

## Security

If you discover any security-related issues, please email security@kpirnie.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credits

- [Kevin Pirnie](https://github.com/kpirnie)
- [Tailwind CSS](https://tailwindcss.com/)
- [esbuild](https://esbuild.github.io/)
- [KP WP Field Framework](https://github.com/kpirnie/kpt-wpfieldframework)
- All contributors

## Support

- **Issues**: [GitHub Issues](https://github.com/kpirnie/wptheme-podnest-vr/issues)
