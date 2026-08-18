# TYPO3 13 and 14 feature audit

Last reviewed: 2026-08-18 against TYPO3 13.4 and 14.3 documentation and the
Core changelog shipped with TYPO3 14.3.6.

Supported baselines at that review are TYPO3 13.4.34 and TYPO3 14.3.6. Older
patch/sprint releases are intentionally excluded because of known security
advisories or because they predate the v14 APIs used by the package.

The review covered all Core feature changelog entries for these majors (148 for
TYPO3 13 and 221 for TYPO3 14). This document records the features that affect a
reusable site package. Backend-only user interface changes, internal APIs,
database drivers, infrastructure adapters and optional system extensions are
not copied into the extension merely because Core provides them.

Official indexes:

- [TYPO3 13 changelog](https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog-13.html)
- [TYPO3 14 changelog](https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog-14.html)
- [Fluid components](https://docs.typo3.org/other/typo3fluid/fluid/main/en-us/Usage/Components.html)

## Implemented shared baseline

| Area | Core feature | Implementation |
|---|---|---|
| Composition | Site Sets, TypoScript and Page TSconfig providers (13.1) | `Configuration/Sets/SitePackage` is the only integration entry point. |
| Page rendering | `PAGEVIEW` and `page-content` (13.1) | `TypoScript/Page.typoscript` resolves backend-layout templates and named content areas. |
| Settings | Site Settings editor (13.3) | Typed definitions and defaults live beside the Site Set. |
| Content types | Automatic content-type/TCA integration and record transformation (13.x) | Content Blocks owns definitions; templates consume transformed `data` and page-content records. |
| Fluid components | Fluid 4.3 component API | The global `site:*` collection uses strict `f:argument` contracts, default and nested components, and `f:slot`. |
| Component registration | Configuration-based collections and namespaces (14.1) | TYPO3 14 loads `Configuration/Fluid/*.php`; TYPO3 13 uses the class-based collection registered in `ext_localconf.php`. Core explicitly supports shipping both. |
| Content rendering | `f:render.contentArea` (14.2) | TYPO3 14 overrides only `site:content.area` and uses the event-aware ViewHelper. TYPO3 13 resolves the same component API to its compatible `f:cObject` loop. |
| Fluid tooling | XSD generation (13.2), component XSD support and `fluid:analyze` (14.2) | Components use `*.fluid.html`; validation commands are part of the documented workflow. |
| Assets | Asset ViewHelpers and public extension resources | Global CSS and per-block CSS use `f:asset.css`; Content Blocks publishes block assets. |
| Images | Core WebP, AVIF and SVG crop support (13.x) | No custom image processor is imposed; `f:image` delegates formats and processing to project/Core configuration. |
| Editing | Site settings, RTE processing, backend layouts and inherited page permissions | Defaults are file-based; actual backend/frontend users, mounts and groups remain installation data. |

## Deliberate compatibility boundaries

Some TYPO3 14 features are good upgrades for a v14-only package but cannot be
used in shared Fluid or configuration without breaking TYPO3 13:

- `f:page.title`, `f:page.meta`, `f:page.headerData` and `f:page.footerData`
  (14.0) are reserved for templates that actually need to manipulate those
  values. The current title, metadata and favicon are correctly handled by Core
  and TypoScript.
- The generic `.fluid.*` resolver, Fluid union argument types and `f:srcset`
  are not used by the shared page templates. Components use `.fluid.html`
  because Fluid components already require and support that convention on the
  TYPO3 13 fallback.
- `f:render.record`, `f:render.text` and the content-area rendering event are
  v14-only. The v14 component override can use them; shared Content Block
  templates must stay on APIs available in both majors.
- Content-type restrictions per backend-layout column (14.1) are not hard-coded.
  The valid list depends on the project and its installed Content Blocks. Add
  `allowedContentTypes`/`disallowedContentTypes` when the project identity and
  editor workflow are known.
- Route enhancers provided by Site Sets (14.1) need a real routing model and are
  therefore not shipped as placeholder configuration.
- `ext_emconf.php` is retained for TYPO3 13 and non-Composer extension metadata,
  even though Composer metadata is authoritative in this project.

## Features that remain project or installation concerns

- Backend accounts, frontend users/groups, database/file mounts, access lists
  and permission records contain local UIDs and must be provisioned per project.
- SEO, Forms, Indexed Search, redirects, workspaces, MFA and scheduler/messenger
  functionality are optional Core packages or operational choices. Add their
  Site Sets and configuration only when the project requires them.
- Cache backends, Redis credentials, trusted proxies, CSP reporting, mail,
  database configuration, image conversion defaults and deployment topology
  belong in environment/system configuration, not a distributable site package.
- Backend UX features such as the page creation wizard, content type usage
  report, QR preview, language selectors and file-browser improvements work
  automatically when the matching Core version is installed.

## TYPO3 14-only follow-up options

When a project intentionally drops TYPO3 13 support, it can remove
`ComponentCollection.php` and the namespace fallback from `ext_localconf.php`.
It may then adopt v14-only ViewHelpers throughout, use union component argument
types, explicitly depend on the Content Blocks extension bundle, and define
content restrictions per backend-layout column.

Content Blocks Site Set integration arrived in Content Blocks 2.3, while the
TYPO3 13 dependency line uses Content Blocks 1.x. The shared Site Set therefore
does not depend on `yoursitepackage/content-blocks-bundle`; on a v14-only project,
add that bundle (or selected block sets) to the site's dependencies.

## Verification commands

```bash
composer validate --strict
vendor/bin/typo3 lint:yaml packages/your_sitepackage
vendor/bin/typo3 content-blocks:lint
vendor/bin/typo3 fluid:analyze
vendor/bin/typo3 fluid:schema:generate
vendor/bin/typo3 cache:warmup
```

`fluid:analyze` is a TYPO3 14.2 command and scans `*.fluid.*` automatically.
On TYPO3 13, use `fluid:schema:generate` and render representative frontend and
backend pages as the Fluid integration checks.
