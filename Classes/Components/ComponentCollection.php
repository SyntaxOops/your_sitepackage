<?php

declare(strict_types=1);

namespace SyntaxOops\YourSitepackage\Components;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3Fluid\Fluid\Core\Component\AbstractComponentCollection;
use TYPO3Fluid\Fluid\View\TemplatePaths;

/**
 * TYPO3 13 fallback for the configuration-based collection used by TYPO3 14.
 */
final class ComponentCollection extends AbstractComponentCollection
{
    public function getTemplatePaths(): TemplatePaths
    {
        $templatePaths = new TemplatePaths();
        $templatePaths->setTemplateRootPaths([
            ExtensionManagementUtility::extPath('your_sitepackage', 'Resources/Private/Components'),
        ]);

        return $templatePaths;
    }
}
