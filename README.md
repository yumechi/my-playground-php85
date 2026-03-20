# my-playground-php85

> Note: これは個人の実験・学習用リポジトリです。コードの品質や動作は保証しません。

PHP 8.5.3 (podman compose) でコードゴルフを行うリポジトリです。

## 開発環境

2026/01/27 現在、個人で Cursor と Claude Code を使って開発しています。

すべてのアプリケーションは仮想化されたコンテナ上で動作させています。
コンテナランタイムとして Docker または podman に対応しています。

## 実行方法

```bash
# solution.php を実行
make run

# 別ファイルを指定して実行
make run FILE=isbn.php

# 標準入力を渡す
echo "978403814058" | make run
```

## 参照しているツール/フレームワークのライセンス

## ライセンス
