<?php

declare(strict_types=1);

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addTcaSelectItemGroup(
    'tt_content',
    'CType',
    'your_sitepackage',
    'LLL:EXT:your_sitepackage/Resources/Private/Language/locallang.xlf:contentElement.group',
    'before:default',
);
