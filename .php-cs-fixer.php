<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['bootstrap', 'vendor', 'templates', 'migrations', 'config', 'var'])
    ->name('*.php')
    ->notPath('public/index.php')
    ->ignoreDotFiles(true);

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PHP80Migration' => true,
    '@PSR12' => true,
    '@Symfony' => true,
    'array_syntax' => ['syntax' => 'short'],
    'no_unused_imports' => true,
    'declare_strict_types' => true,
    'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
])
    ->setRiskyAllowed(true)
    ->setFinder($finder);