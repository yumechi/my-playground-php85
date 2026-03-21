# PHP AST 検証環境

検証目的や背景は [CLAUDE.md](./CLAUDE.md) を参照。

## セットアップ

```bash
cd ast-testing
make build
```

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

# クリーンアップ
make clean
```
