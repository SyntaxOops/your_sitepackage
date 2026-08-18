<?php

declare(strict_types=1);

use SyntaxOops\YourSitepackage\Components\ComponentCollection;
use TYPO3\CMS\Core\Information\Typo3Version;

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['your_sitepackage'] =
    'EXT:your_sitepackage/Configuration/RTE/Default.yaml';

// TYPO3 14 loads Configuration/Fluid/Namespaces.php and the configured
// collection. TYPO3 13 needs the class-based collection registered globally.
if ((new Typo3Version())->getMajorVersion() < 14) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['site'][] = ComponentCollection::class;
}
