# TYPO3 project backbone

This extension is a reusable starting point for TYPO3 13 LTS and TYPO3 14
projects. It owns project-specific presentation and integration code while site
records, secrets, environments and deployment configuration remain in the host
project.

## Compatibility

- TYPO3 `13.4.34+` with Content Blocks `1.6.3+`
- TYPO3 `14.3.6+` with Content Blocks `2.4.8+`
- PHP `8.2+` (the installed TYPO3 Core determines the supported upper bound)
- Composer-based installation with the web document root pointing to `public/`

Review the current [TYPO3 system requirements](https://docs.typo3.org/permalink/t3coreapi:system-requirements)
for the selected Core version. A normal production installation also needs the
required PHP extensions, a supported database, and GraphicsMagick or ImageMagick
when TYPO3 should process images.

These lower bounds are the secure, tested patch levels at the last review. Do
not loosen them to an older sprint/security release merely to satisfy an
existing lock file; update that project first.

## Install

Add the extension as a path repository while developing it inside a project:

```json
{
    "repositories": {
        "local-packages": {
            "type": "path",
            "url": "./packages/*"
        }
    }
}
```

Then install and initialize it:

```bash
composer require syntaxoops/your-sitepackage:@dev
vendor/bin/typo3 extension:setup
vendor/bin/typo3 cache:flush
```

Add the Site Set to each site's `config/sites/<site>/config.yaml` or select it in
the Sites module:

```yaml
dependencies:
  - syntaxoops/your-sitepackage
```

Use the Sites setup module to adjust the logo, favicon, template path, contact
email and the settings inherited from Fluid Styled Content and Frontend Login.

## What belongs here

- `Configuration/Sets/SitePackage`: composable Site Set, site settings,
  TypoScript, Page TSconfig and version-controlled backend layouts
- `Configuration/RTE`: the project RTE preset; keep the secure Core processing
  import when changing the editor toolbar
- `ContentBlocks`: project-owned content, page and record type definitions
- `Resources/Private/Components`: shared TYPO3 13/14 Fluid components
- `Resources/Private/ComponentsV14`: targeted v14 component overrides
- `Resources/Private/Templates`: PAGEVIEW layouts and page templates
- `Resources/Public`: source-controlled frontend assets and icons
- `Classes`: PSR-4 PHP classes when the project needs application logic

The included Hero Content Block is a reference implementation. Copy its folder
to create another block, give the block a unique `name` and `typeName`, and adapt
its fields, labels, templates and assets together.

After adding or changing Content Block fields, run `extension:setup` so TYPO3 can
apply the generated database schema. Content Blocks are copied between projects
as complete folders; do not split their assets or templates into unrelated
directories.

## Fluid components

The global `site:*` component collection is available in page templates,
Content Blocks and other Fluid rendering contexts. It currently provides:

- `site:atom.button`, including its default slot;
- `site:content.area`;
- `site:navigation.main` and `site:navigation.breadcrumb`;
- `site:page.header` and `site:page.footer`.

Every argument is declared with `f:argument`; undeclared arguments fail instead
of leaking implicitly into the component. Keep component calls one-way and do
not split stateful form ViewHelpers across component boundaries, because those
ViewHelpers rely on their parent rendering context.

TYPO3 13 resolves the PHP `ComponentCollection`; TYPO3 14 resolves the matching
configuration-based collection and global namespace. The v14 collection also
overrides `site:content.area` with the event-aware `f:render.contentArea`
implementation. See [the feature compatibility audit](Documentation/TYPO3-13-14-Feature-Audit.md)
before introducing version-specific APIs.

## Frontend users and protected content

The Site Set includes `typo3/felogin`, but user records and access groups are
intentionally not shipped with this extension because they are installation data
and their UIDs differ between projects.

For each site:

1. Create a dedicated sysfolder for `fe_users` and `fe_groups` records.
2. Set `felogin.pid` in Sites > Setup to that folder's UID. Keep
   `felogin.recursive` at `0` unless nested storage is intentional.
3. Create explicit frontend groups and assign users to them.
4. Restrict pages/content to those explicit groups. Avoid "Show at any login" in
   multi-site installations because it can grant access to users from another
   site.
5. Add the Frontend Login content element to the intended login page and test
   login, logout, password recovery, redirects and cache behavior as a real user.

TYPO3 Core does not provide frontend self-registration. Add and review a
dedicated registration extension only when the project requires that workflow.

## Backend editors and page permissions

Backend user groups are database records and should follow least privilege. For
an editor role, explicitly review at least:

- allowed backend modules and page types;
- read/write access to `pages`, `tt_content`, `sys_file` and
  `sys_file_reference` as required;
- excluded fields introduced by Content Blocks;
- explicitly allowed `tt_content.CType` values, including
  `your_sitepackage_hero`;
- database mounts, file mounts, languages and file operations;
- page ownership and permissions at every mount root.

The shipped Page TSconfig copies the parent page's group, group permissions and
"everybody" permissions to newly created child pages. Establish the correct
owner group and permissions on each mount root first, then verify the complete
workflow by switching to the editor account.

Do not put production users, password hashes or fixed record UIDs into this Git
repository. TYPO3 currently has no official file-based deployment format for
backend user-group rights; use a documented project provisioning/import process
when those records must be reproduced.

## Turn this into a project-specific package

Before starting a client project, replace all identity placeholders consistently:

- Composer package: `syntaxoops/your-sitepackage`
- extension key: `your_sitepackage`
- PHP namespace: `SyntaxOops\\YourSitepackage`
- site-setting prefix: `YourSitepackage`
- Site Set name and Content Block vendor/names
- extension title, URLs, logo, favicon and language labels

Keep `colPos` numbers consistent across backend layouts (`0` main, `1` stage,
`2` sidebar, `3` footer). PAGEVIEW selects a page template from the backend
layout identifier, so every new layout also needs a matching file in
`Resources/Private/Templates/Pages/`.

## Deployment checklist

```bash
composer validate --strict
composer install --no-dev --classmap-authoritative
vendor/bin/typo3 extension:setup
vendor/bin/typo3 cache:warmup
vendor/bin/typo3 fluid:analyze
vendor/bin/typo3 fluid:schema:generate
```

Publish `Resources/Public` using the deployment process supported by the target
TYPO3 version. On TYPO3 14, `vendor/bin/typo3 asset:publish` can repair published
assets. Keep `config/` read-only to the web process in production unless backend
editing of site/system configuration is an explicit requirement.
