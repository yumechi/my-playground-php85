<?php

// 変数埋め込み echo — deprecated パターン検証
// "${var}" 形式は PHP 8.2 で deprecated

$name = "world";

// パターン1: "${var}" — PHP 8.2+ で deprecated
echo "Hello ${name}!";

// パターン2: "{$var}" — 推奨形式
echo "Hello {$name}!";

// パターン3: 直接埋め込み "$var"
echo "Hello $name!";
