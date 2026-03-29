# PHP AST 検証環境

検証目的や背景は [CLAUDE.md](./CLAUDE.md) を参照。

## セットアップ

```bash
cd ast-testing
make build
```

## 使い方

```bash
# 全ケース一括実行（結果は results/ に保存）
make all

# --- php-ast (ast\parse_code) ---
make interpolation          # 変数埋め込み deprecated 検証
make comments               # コメント差分（コメントあり）
make comments-no-comments   # コメント差分（コメントなし、比較用）
make docblock-interpolation # DocBlock 内の変数埋め込み検証

# --- nikic/php-parser ---
make parser-interpolation              # 変数埋め込み deprecated 検証
make parser-comments                   # コメント差分（コメントあり）
make parser-comments-no-comments       # コメント差分（コメントなし、比較用）
make parser-docblock-interpolation     # DocBlock 内の変数埋め込み検証

# --- AST ハッシュ比較 ---
make hash-compare           # ${var} -> {$var} 置換前後の AST ハッシュ比較

# --- ユーティリティ ---
make run-one VER=php85 FILE=cases/comment_diff.php   # 単一バージョン指定
make clean-results                                     # 結果ファイルを削除
make clean                                             # コンテナ・イメージを削除
```

## 結果の確認

実行結果は `results/{ケース名}/{バージョン}.txt` に保存されます。

```bash
# バージョン間の diff
diff results/interpolation/php81.txt results/interpolation/php82.txt

# php-ast vs php-parser の比較
diff results/interpolation/php81.txt results/parser-interpolation/php81.txt

# コメント有無の diff
diff results/parser-comments/php85.txt results/parser-comments-no-comments/php85.txt

# AST ハッシュ比較の結果確認
cat results/hash-compare/php81.txt
diff results/hash-compare/php81.txt results/hash-compare/php82.txt
```
