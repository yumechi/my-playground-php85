<?php

$version = 100;

echo "=== PHP " . PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION . " ===\n";
echo "ast extension version: " . phpversion('ast') . "\n";
echo "AST version: " . $version . "\n";
echo str_repeat("-", 60) . "\n\n";

function serialize_ast(ast\Node|string|int|float|null $node): string {
    if ($node === null) {
        return "null";
    }
    if (is_scalar($node)) {
        return var_export($node, true);
    }
    $parts = [];
    $parts[] = ast\get_kind_name($node->kind);
    $parts[] = "flags:" . $node->flags;
    foreach ($node->children as $key => $child) {
        if ($child instanceof ast\Node) {
            $parts[] = $key . ":" . serialize_ast($child);
        } elseif (is_array($child)) {
            $items = [];
            foreach ($child as $item) {
                $items[] = serialize_ast($item);
            }
            $parts[] = $key . ":[" . implode(",", $items) . "]";
        } else {
            $parts[] = $key . ":" . var_export($child, true);
        }
    }
    return "(" . implode("|", $parts) . ")";
}

function ast_hash(string $code, int $version): string {
    $ast = ast\parse_code($code, $version);
    $serialized = serialize_ast($ast);
    return hash('sha256', $serialized);
}

$patterns = [
    'A' => [
        'label' => 'code "${var}" -> "{$var}"',
        'before' => 'cases/hash_code_before.php',
        'after' => 'cases/hash_code_after.php',
    ],
    'B' => [
        'label' => 'docComment "${var}" -> "{$var}"',
        'before' => 'cases/hash_docblock_before.php',
        'after' => 'cases/hash_docblock_after.php',
    ],
    'C' => [
        'label' => 'code + docComment both "${var}" -> "{$var}"',
        'before' => 'cases/hash_both_before.php',
        'after' => 'cases/hash_both_after.php',
    ],
];

foreach ($patterns as $id => $pattern) {
    $beforeCode = file_get_contents($pattern['before']);
    $afterCode = file_get_contents($pattern['after']);

    $beforeHash = ast_hash($beforeCode, $version);
    $afterHash = ast_hash($afterCode, $version);

    $same = $beforeHash === $afterHash;

    echo "[Pattern {$id}] {$pattern['label']}\n";
    echo "  before: sha256={$beforeHash}\n";
    echo "  after:  sha256={$afterHash}\n";
    echo "  result: " . ($same ? "SAME" : "CHANGED") . "\n\n";
}
