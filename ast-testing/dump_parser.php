<?php

require '/opt/deps/vendor/autoload.php';

use PhpParser\ParserFactory;
use PhpParser\NodeDumper;

if ($argc < 2) {
    fprintf(STDERR, "Usage: php dump_parser.php <file>\n");
    exit(1);
}

$file = $argv[1];
if (!file_exists($file)) {
    fprintf(STDERR, "File not found: %s\n", $file);
    exit(1);
}

$code = file_get_contents($file);

echo "=== PHP " . PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION . " (nikic/php-parser) ===\n";
echo "php-parser version: " . \Composer\InstalledVersions::getPrettyVersion('nikic/php-parser') . "\n";
echo "File: " . $file . "\n";
echo str_repeat("-", 60) . "\n\n";

$parser = (new ParserFactory)->createForHostVersion();

try {
    $stmts = $parser->parse($code);
    $dumper = new NodeDumper(['dumpComments' => true]);
    echo $dumper->dump($stmts) . "\n";
} catch (PhpParser\Error $e) {
    echo "Parse error: " . $e->getMessage() . "\n";
}

echo "\n";
