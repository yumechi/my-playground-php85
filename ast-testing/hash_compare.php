<?php

$version = 100;

echo "=== PHP " . PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION . " ===\n";
echo "ast extension version: " . phpversion('ast') . "\n";
echo "AST version: " . $version . "\n";
echo str_repeat("-", 60) . "\n\n";

/** @var string[] docComment 以外にスキップしたいキーがあれば追加 */
const SKIP_KEYS_FOR_DOCCOMMENT = ['docComment'];

function serialize_ast(ast\Node|string|int|float|null $node, bool $skipDocComment = false): string {
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
        if ($skipDocComment && in_array($key, SKIP_KEYS_FOR_DOCCOMMENT, true)) {
            continue;
        }
        if ($child instanceof ast\Node) {
            $parts[] = $key . ":" . serialize_ast($child, $skipDocComment);
        } elseif (is_array($child)) {
            $items = [];
            foreach ($child as $item) {
                $items[] = serialize_ast($item, $skipDocComment);
            }
            $parts[] = $key . ":[" . implode(",", $items) . "]";
        } else {
            $parts[] = $key . ":" . var_export($child, true);
        }
    }
    return "(" . implode("|", $parts) . ")";
}

function ast_hash(string $code, int $version, bool $skipDocComment = false): string {
    $ast = ast\parse_code($code, $version);
    $serialized = serialize_ast($ast, $skipDocComment);
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

    // docComment 込み
    $beforeHash = ast_hash($beforeCode, $version, false);
    $afterHash = ast_hash($afterCode, $version, false);
    $same = $beforeHash === $afterHash;

    // docComment 除外
    $beforeHashNoDoc = ast_hash($beforeCode, $version, true);
    $afterHashNoDoc = ast_hash($afterCode, $version, true);
    $sameNoDoc = $beforeHashNoDoc === $afterHashNoDoc;

    echo "[Pattern {$id}] {$pattern['label']}\n";
    echo "  with docComment:\n";
    echo "    before: sha256={$beforeHash}\n";
    echo "    after:  sha256={$afterHash}\n";
    echo "    result: " . ($same ? "SAME" : "CHANGED") . "\n";
    echo "  without docComment:\n";
    echo "    before: sha256={$beforeHashNoDoc}\n";
    echo "    after:  sha256={$afterHashNoDoc}\n";
    echo "    result: " . ($sameNoDoc ? "SAME" : "CHANGED") . "\n\n";
}
