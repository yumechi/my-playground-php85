<?php

/**
 * Greet with ${name} interpolation
 * @param string $name
 * @return string formatted as "Hello ${name}!"
 */
function greet(string $name): string {
    return "Hello {$name}!";
}
