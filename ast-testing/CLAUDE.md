## 検証目的

PHP の `php-ast` 拡張を使い、PHPバージョン間（8.1〜8.5）での AST（抽象構文木）の違いを検証する。

### 検証ケース

1. **変数埋め込み echo の deprecated**
   - `"${var}"` 形式が PHP 8.2 で deprecated になった件
   - `"${var}"` / `"{$var}"` / `"$var"` の3形式が各バージョンの AST でどう表現されるかを比較

2. **コメント差分**
   - 同じロジックでコメント有無のコードの AST 差分を確認
   - `--with-comments` オプション有無での `ast\parse_code` の出力差分を確認

## 環境

- PHP 8.1〜8.5 の5バージョンを podman compose で管理
- PHP 8.1: `pecl install ast`
- PHP 8.2+: PIE (`pie install nikic/php-ast`)

## 実行方法

- `make build` — 全イメージをビルド
- `make all` — 全ケースを全バージョンで実行
- `make run-one VER=php85 FILE=cases/file.php OPTS=--with-comments` — 単一バージョン指定
