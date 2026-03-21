<?php

if ($argc < 2) {
    fprintf(STDERR, "Usage: php dump_ast.php <file> [--with-comments]\n");
    exit(1);
}

$file = $argv[1];
$withComments = in_array('--with-comments', $argv, true);

if (!file_exists($file)) {
    fprintf(STDERR, "File not found: %s\n", $file);
    exit(1);
}

$code = file_get_contents($file);
$version = ast\LATEST_VERSION;

echo "=== PHP " . PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION . " ===\n";
echo "ast extension version: " . phpversion('ast') . "\n";
echo "AST version: " . $version . "\n";
echo "File: " . $file . "\n";
echo "Comments: " . ($withComments ? "ON" : "OFF") . "\n";
echo str_repeat("-", 60) . "\n\n";

$flags = 0;
if ($withComments) {
    $flags = ast\flags\PARSE_COMMENTS;
}

try {
    $ast = ast\parse_code($code, $version, $flags);
    echo ast_dump($ast, AST_DUMP_LINENOS);
} catch (ParseError $e) {
    echo "ParseError: " . $e->getMessage() . "\n";
} catch (CompileError $e) {
    echo "CompileError: " . $e->getMessage() . "\n";
}

echo "\n";
