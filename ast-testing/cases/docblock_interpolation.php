<?php

/**
 * DocBlock with ${name} interpolation
 * Also {$name} and $name patterns
 */
function greet(string $name): string {
    return "Hello ${name}!";  // deprecated pattern
}

/**
 * DocBlock with {$value} pattern
 */
function format(int $value): string {
    return "Value: {$value}";  // recommended pattern
}

/**
 * DocBlock with $result direct reference
 */
function show(string $result): void {
    echo "Result: $result";  // simple pattern
}
