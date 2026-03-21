# PHP AST 検証環境

PHP 8.1〜8.5 の5バージョンで `php-ast` 拡張を使い、バージョン間の AST 差分を検証する。

## セットアップ

```bash
cd ast-testing
make build
```

- PHP 8.1: `pecl install ast`
- PHP 8.2+: PIE (`pie install nikic/php-ast`)

## 使い方

```bash
# 全ケース一括実行
make all

# 個別実行
make interpolation          # 変数埋め込み deprecated 検証
make comments               # コメント差分 (オプションなし)
make comments-with          # コメント差分 (--with-comments)
make comments-no-comments   # コメントなしコード (比較用)

# 単一バージョン指定
make run-one VER=php85 FILE=cases/comment_diff.php OPTS=--with-comments
```

## 検証ケース

### 1. 変数埋め込み echo の deprecated (`cases/deprecated_interpolation.php`)

`"${var}"` 形式が PHP 8.2 で deprecated になった件。以下3パターンの AST 差分を比較する。

- `"${var}"` — PHP 8.2+ で deprecated
- `"{$var}"` — 推奨形式
- `"$var"` — 直接埋め込み

### 2. コメント差分 (`cases/comment_diff.php`, `cases/comment_diff_no_comments.php`)

コメント有無による AST の違いを検証する。

- `make comments` — コメントありコードを `--with-comments` なしでダンプ
- `make comments-with` — コメントありコードを `--with-comments` ありでダンプ
- `make comments-no-comments` — コメントなしコードをダンプ（比較用）
