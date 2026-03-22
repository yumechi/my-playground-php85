## 検証目的

PHP の `php-ast` 拡張および `nikic/php-parser` を使い、PHPバージョン間（8.1〜8.5）での AST（抽象構文木）の違いを検証する。

### 検証ケース

1. **変数埋め込み echo の deprecated**
   - `"${var}"` 形式が PHP 8.2 で deprecated になった件
   - `"${var}"` / `"{$var}"` / `"$var"` の3形式が各バージョンの AST でどう表現されるかを比較
   - php-ast: PHP 8.2+ で `AST_VAR` に `flags: 1` が付与される
   - php-parser: バージョン間の差分なし（独自パーサーのため）

2. **コメント差分**
   - 同じロジックでコメント有無のコードの AST 差分を確認
   - php-ast は通常コメント（`//`, `/* */`）を保持しない（`zend_ast` の仕様）。DocBlock（`/** */`）のみ `docComment` として保持
   - php-parser はすべてのコメントを `comments` 属性として保持

3. **DocBlock 内の変数埋め込み形式**
   - DocBlock 内に `${var}`, `{$var}`, `$var` を記載した場合の AST を比較
   - DocBlock はただの文字列として保持されるため、バージョン間で AST 差分なし

4. **AST ハッシュ比較による置換影響検証**
   - PHPerKaigi 2026 松尾篤氏の発表内容の再現検証
   - `"${var}"` → `"{$var}"` 置換前後の AST を SHA-256 ハッシュで比較
   - **検証結果**:
     - コード内の置換: PHP 8.1 では SAME、PHP 8.2+ では CHANGED（deprecated フラグの影響）
     - DocComment 内の置換: 全バージョンで CHANGED（文字列がそのまま AST に含まれるため）
     - コード + DocComment 両方: 全バージョンで CHANGED

## 環境

- PHP 8.1〜8.5 の5バージョンを podman compose で管理
- PHP 8.1: `pecl install ast`
- PHP 8.2+: PIE (`pie install nikic/php-ast`)
- `nikic/php-parser` v5 を composer でインストール（イメージ内 `/opt/deps/` に配置）

## ダンプスクリプト

- `dump_ast.php` — php-ast (`ast\parse_code`) による AST ダンプ
- `dump_parser.php` — nikic/php-parser (`PhpParser`) による AST ダンプ（コメント含む）
- `hash_compare.php` — 置換前後の AST ハッシュ（SHA-256）比較

## 実行方法

- `make build` — 全イメージをビルド
- `make all` — 全ケースを全バージョンで実行し `results/` に保存
- `make run-one VER=php85 FILE=cases/file.php` — 単一バージョン指定
- `make clean-results` — 結果ファイルを削除
