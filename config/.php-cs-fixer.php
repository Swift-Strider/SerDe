<?php

$sep = DIRECTORY_SEPARATOR;
$root = realpath(__DIR__ . $sep . '..');

$finder = PhpCsFixer\Finder::create()
	->in($root . $sep . 'src');

$config = new PhpCsFixer\Config();

return $config
	->setRules([
		'@Symfony' => true,
		'phpdoc_to_comment' => [
			'ignored_tags' => ['var']
		]
	])
	->setIndent("\t")
	->setFinder($finder);
