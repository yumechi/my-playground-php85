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
$version = 100;

echo "=== PHP " . PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION . " ===\n";
echo "ast extension version: " . phpversion('ast') . "\n";
echo "AST version: " . $version . "\n";
echo "File: " . $file . "\n";
if ($withComments) {
    echo "Comments: NOT SUPPORTED in ast " . phpversion('ast') . "\n";
}
echo str_repeat("-", 60) . "\n\n";

function dump_ast_node(ast\Node|string|int|float|null $node, int $indent = 0): string {
    $prefix = str_repeat("  ", $indent);
    if ($node === null) {
        return $prefix . "null\n";
    }
    if (is_scalar($node)) {
        return $prefix . var_export($node, true) . "\n";
    }
    $kindName = ast\get_kind_name($node->kind);
    $result = $prefix . "$kindName";
    if ($node->flags) {
        $result .= " (flags: {$node->flags})";
    }
    $result .= " @ line {$node->lineno}\n";
    foreach ($node->children as $key => $child) {
        $result .= $prefix . "  $key:\n";
        if ($child instanceof ast\Node) {
            $result .= dump_ast_node($child, $indent + 2);
        } elseif (is_array($child)) {
            foreach ($child as $i => $item) {
                $result .= dump_ast_node($item, $indent + 2);
            }
        } else {
            $result .= $prefix . "    " . var_export($child, true) . "\n";
        }
    }
    return $result;
}

try {
    $ast = ast\parse_code($code, $version);
    echo dump_ast_node($ast);
} catch (ParseError $e) {
    echo "ParseError: " . $e->getMessage() . "\n";
} catch (CompileError $e) {
    echo "CompileError: " . $e->getMessage() . "\n";
}

echo "\n";
