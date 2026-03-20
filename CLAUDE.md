## コミュニケーション言語

このプロジェクトでは日本語でコミュニケーションを取ります。日本語を利用できない場合のみ英語でコミュニケーションします。

## プロジェクトの目的

PHP 8.5.3 (podman compose) でコードゴルフを行うリポジトリ。

## 実行方法

- `make run` — `solution.php` を実行（`podman compose run --rm php php solution.php`）
- `make run FILE=other.php` — 別ファイルを指定して実行
- 標準入力: `echo "入力" | make run`

## 利用技術

- PHP 8.5.3-cli (docker.io/library/php:8.5.3-cli)
- podman compose (Docker との互換性を保つこと)

## プロジェクト構成

```
.
├── CLAUDE.md                    # プロジェクト説明・ルール
├── Makefile                     # make run コマンド定義
├── compose.yaml                 # PHP 8.5.3-cli コンテナ定義
├── php.ini                      # error_reporting 設定
└── *.php                        # 問題の解答コード
```
