# VR Content: Post Content

Content for the Marquee, Feature, How It Works Step, Security Item, and Pricing Tier posts under **VR Content** in wp-admin. Prices are placeholders. Set menu order with the **Order** field.

## Marquee

Title only, in this order.

1. Pixel-for-Pixel Diffs
2. Expected Content Checks
3. AI Verdicts: Pass · Warn · Fail
4. Anthropic or OpenAI-Compatible
5. Same-Host Spider
6. PDF Run Reports
7. Emailed Reports
8. Manual Pass with Note
9. Per-URL Basic Auth
10. Mandatory TOTP 2FA
11. Full Audit Log
12. Multi-Arch Container Images

## Features

The **Excerpt** is the card text. Pick the **Icon** in the Feature Card box.

| Order | Title | Icon | Excerpt |
|---|---|---|---|
| 1 | A vs B Comparisons | Compare | Screenshot two URLs in headless Chromium, diff them pixel for pixel, and check the content you care about is on both sides. |
| 2 | AI Verdicts | AI | Anthropic or any OpenAI-compatible model reads the screenshots, the diff, and the page text, and returns pass, warn, or fail with its reasoning. |
| 3 | Expected Content | Content check | List the strings a page must contain. A missing one fails the comparison, whatever the pixels or the model say. |
| 4 | Same-Host Spider | Spider | After the comparisons, walk the site to a set depth and page cap, and report every link that answered with a 4xx or 5xx. |
| 5 | PDF Run Reports | Report | Every run downloads as a PDF: the tallies, then each comparison with its images side by side, the AI review, and any notes. |
| 6 | Emailed Reports | Email | Send each finished run to the project's recipients with the PDF attached, using an email template you can edit. |
| 7 | Manual Pass with Note | Manual pass | Mark a known, acceptable difference as a pass and say why. The run's tallies follow, and the automatic result is kept alongside. |
| 8 | Per-URL Basic Auth | Lock | Staging behind .htpasswd? Credentials are stored encrypted and scoped to that URL's own origin, the way a browser would use them. |
| 9 | Live Dashboard | Dashboard | Runs, pass / warn / fail totals, failed projects, and recent spider findings, refreshing while runs are in progress. |

## How It Works

The **Excerpt** is the step text. Pick the **Icon** and fill in the **Output** in the How It Works Step box. Steps are numbered in menu order.

| Order | Title | Icon | Output | Excerpt |
|---|---|---|---|---|
| 1 | Capture | Capture | baseline.png · candidate.png | Headless Chromium loads both URLs, with per-URL basic auth, frozen animations, and masked selectors for sliders and cookie banners, then screenshots them. |
| 2 | Compare | Pixel diff | diff.png · 4.8% · 5/5 | A pixel-for-pixel diff with a tolerance and threshold, plus a check that every expected string is in the rendered text of both pages. |
| 3 | Judge | AI | verdict: warn · 0.87 | The screenshots, the diff, the measurements, and both pages' text go to your AI model, which returns a verdict, a confidence, a summary, and its findings. |
| 4 | Report | Report | run-42.pdf · emailed | Results land as each comparison finishes. Download the run as a PDF, email it to the project's recipients, or mark a known change as a manual pass. |

## Security

The **Excerpt** is the card text. Pick the **Icon** in the Security Card box.

| Order | Title | Icon | Excerpt |
|---|---|---|---|
| 1 | Mandatory TOTP 2FA | Shield | Every account enrolls an authenticator, with twelve single-use recovery codes and optional remembered browsers. |
| 2 | Argon2id Passwords | Key | Passwords are hashed with Argon2id, and sessions live server-side with a hard seven-day ceiling. |
| 3 | Encrypted Secrets | Lock | Basic auth credentials, API keys, and the SMTP password are encrypted at rest, so a copy of the database is not a copy of your secrets. |
| 4 | Signed Screenshot Links | Signed link | Screenshots are only served with a signed token that covers one run and lapses after an hour. |
| 5 | Throttling & Lockout | Clock | Sign-in and 2FA attempts are limited per address and per account, and every form post carries a CSRF token. |
| 6 | Private Network Guard | Network | URLs that resolve to private, loopback, or reserved addresses are refused when they are saved, and again when they are captured. |
| 7 | Roles & Assignment | Users | Admin, Manager, Trial, and User levels, with project assignment deciding who sees what. |
| 8 | Full Audit Log | Audit list | Every change is recorded with who, when, and from where, plus the values before and after. Secrets are only noted as changed. |

## Pricing Tiers

Content goes in the editor. Everything else goes in the Pricing Tier box. In the features list, a line starting with `x:` shows as not included.

### Order 1: Trial

- **Content:** Try it on one real page, free. Trial accounts are set up by hand, so drop us a note.
- **Tier label:** Try It
- **Price:** 0
- **Price unit:** blank
- **Annual price:** blank
- **Featured:** unchecked
- **Button text:** Start a Free Trial
- **Button plan:** trial
- **Features list:**

```
1 project
1 comparison
5 runs a day
Pixel diff & expected content checks
Per-URL basic auth
x: AI verdicts
x: Spider
x: PDF & emailed reports
```

### Order 2: Paid

- **Content:** Every feature, no caps, hosted and kept up to date for you.
- **Tier label:** Hosted
- **Price:** 49
- **Price unit:** / month
- **Annual price:** 490
- **Annual price unit:** / year
- **Badge:** Most Popular
- **Featured:** checked
- **Button text:** Get Started
- **Button plan:** paid
- **Features list:**

```
Unlimited projects & comparisons
Unlimited runs
AI verdicts
Same-host spider
PDF & emailed reports
Team users & project assignment
Support at support.podnest.us
```

### Order 3: Source Code

- **Content:** Run it on your own servers. Get the full source and every update pushed to it.
- **Tier label:** Self-Hosted
- **Price:** 2499
- **Price unit:** one-time
- **Annual price:** blank
- **Featured:** unchecked
- **Button text:** Buy the Source
- **Button plan:** source
- **Features list:**

```
Private GitHub repository access
Every update as it's pushed
Multi-arch container images
Generic support via GitHub Issues
x: Personalized support (extra cost)
```
