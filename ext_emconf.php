<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Your Site Package',
    'description' => 'Reusable TYPO3 project backbone',
    'category' => 'templates',
    'version' => '1.0.1',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'Haythem Daoud',
    'author_email' => 'hello@haythemdaoud.dev',
    'constraints' => [
        'depends' => [
            'php' => '8.2.0-8.5.99',
            'typo3' => '13.4.34-14.99.99',
            'content_blocks' => '1.6.3-2.99.99',
            'felogin' => '13.4.34-14.99.99',
            'fluid' => '13.4.34-14.99.99',
            'fluid_styled_content' => '13.4.34-14.99.99',
            'frontend' => '13.4.34-14.99.99',
            'rte_ckeditor' => '13.4.34-14.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
