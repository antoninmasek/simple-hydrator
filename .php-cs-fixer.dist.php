<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setRules([
        '@Symfony'                          => true,
        'phpdoc_no_empty_return'            => false,
        'array_syntax'                      => ['syntax'  => 'short'],
        'yoda_style'                        => false,
        'binary_operator_spaces'            => [
            'operators' => [
                '=>' => 'align',
                '='  => 'align',
            ],
        ],
        'concat_space'                      => ['spacing' => 'one'],
        'increment_style'                   => ['style'   => 'post'],
        'not_operator_with_successor_space' => true,
    ])
    ->setFinder($finder);
