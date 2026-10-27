<?php

use Corpus\PhpCsFixer\Config;

$finder = PhpCsFixer\Finder::create()
	->files()
	->in(__DIR__ . '/src')
	->in(__DIR__ . '/test')
	->in(__DIR__ . '/example')
	->name('*.php');

$config = Config::php7x4();

return $config
	->setRules(
		array_merge(
			[ '@PHPUnit9x1Migration:risky' => true ],
			$config->getRules(),
		)
	)
	->setFinder($finder);


