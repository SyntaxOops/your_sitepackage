# Your Site Package

A reusable site package for TYPO3 13 LTS and TYPO3 14. It includes a Site Set,
PAGEVIEW templates, backend layouts, Fluid components, RTE configuration, and a
sample Hero Content Block.

## Requirements

- PHP 8.2 or newer
- TYPO3 13.4.34+ or 14.3.6+
- Content Blocks 1.6.3+ or 2.4.8+

## Installation

Install the extension with Composer:

```bash
composer require syntaxoops/your-sitepackage
vendor/bin/typo3 extension:setup
```

Add the Site Set in the TYPO3 Sites module or in your site's `config.yaml`:

```yaml
dependencies:
  - syntaxoops/your-sitepackage
```

Configure the logo, favicon, template paths, and other options in the Site
Settings editor.

## Customization

Before using this package for a project, replace the example package name,
extension key, PHP namespace, Site Set name, labels, and branding. The included
Hero Content Block can be copied as a starting point for custom content elements.

## License

GPL-2.0-or-later
