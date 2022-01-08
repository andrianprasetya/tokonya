<?php

$rules = [
    'no_whitespace_in_blank_line' => false,
    'array_syntax' => ['syntax' => 'short'],
    'linebreak_after_opening_tag' => true,
    'line_ending' => false,
    'not_operator_with_successor_space' => false,
    'ordered_imports' => false,
    'phpdoc_order' => false,
];

$finder = \PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/database',
        __DIR__ . '/routes',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new \PhpCsFixer\Config)
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
