COMPOSE := podman compose
VERSIONS := php81 php82 php83 php84 php85
RESULTS  := results

.PHONY: build all interpolation comments comments-no-comments docblock-interpolation docblock-interpolation-exclude parser-comments parser-comments-no-comments parser-interpolation parser-docblock-interpolation hash-compare clean clean-results

# 全イメージをビルド
build:
	$(COMPOSE) build

# 全ケースを全バージョンで実行
all: interpolation comments comments-no-comments docblock-interpolation docblock-interpolation-exclude parser-comments parser-comments-no-comments parser-interpolation parser-docblock-interpolation hash-compare

# --- 変数埋め込み deprecated 検証 ---
interpolation:
	@mkdir -p $(RESULTS)/interpolation
	@echo "=============================="
	@echo " 変数埋め込み (deprecated interpolation)"
	@echo "=============================="
	@for v in $(VERSIONS); do \
		$(COMPOSE) run --rm $$v php dump_ast.php cases/deprecated_interpolation.php 2>/dev/null \
			| tee $(RESULTS)/interpolation/$$v.txt; \
		echo ""; \
	done
	@echo "=> $(RESULTS)/interpolation/ に保存しました"

# --- コメント差分検証 (コメントオプションなし) ---
comments:
	@mkdir -p $(RESULTS)/comments
	@echo "=============================="
	@echo " コメント差分 (WITHOUT --with-comments)"
	@echo "=============================="
	@for v in $(VERSIONS); do \
		$(COMPOSE) run --rm $$v php dump_ast.php cases/comment_diff.php 2>/dev/null \
			| tee $(RESULTS)/comments/$$v.txt; \
		echo ""; \
	done
	@echo "=> $(RESULTS)/comments/ に保存しました"

# --- コメントなしコード (比較用) ---
comments-no-comments:
	@mkdir -p $(RESULTS)/comments-no-comments
	@echo "=============================="
	@echo " コメントなしコード (比較用)"
	@echo "=============================="
	@for v in $(VERSIONS); do \
		$(COMPOSE) run --rm $$v php dump_ast.php cases/comment_diff_no_comments.php 2>/dev/null \
			| tee $(RESULTS)/comments-no-comments/$$v.txt; \
		echo ""; \
	done
	@echo "=> $(RESULTS)/comments-no-comments/ に保存しました"

# --- DocBlock 内の変数埋め込み検証 ---
docblock-interpolation:
	@mkdir -p $(RESULTS)/docblock-interpolation
	@echo "=============================="
	@echo " DocBlock 変数埋め込み (php-ast)"
	@echo "=============================="
	@for v in $(VERSIONS); do \
		$(COMPOSE) run --rm $$v php dump_ast.php cases/docblock_interpolation.php 2>/dev/null \
			| tee $(RESULTS)/docblock-interpolation/$$v.txt; \
		echo ""; \
	done
	@echo "=> $(RESULTS)/docblock-interpolation/ に保存しました"

# --- DocBlock 除外で変数埋め込み検証 ---
docblock-interpolation-exclude:
	@mkdir -p $(RESULTS)/docblock-interpolation-exclude
	@echo "=============================="
	@echo " DocBlock 変数埋め込み (exclude docComment)"
	@echo "=============================="
	@for v in $(VERSIONS); do \
		$(COMPOSE) run --rm $$v php dump_ast.php cases/docblock_interpolation.php --exclude-doc 2>/dev/null \
			| tee $(RESULTS)/docblock-interpolation-exclude/$$v.txt; \
		echo ""; \
	done
	@echo "=> $(RESULTS)/docblock-interpolation-exclude/ に保存しました"

# --- nikic/php-parser: コメント差分 (コメントあり) ---
parser-comments:
	@mkdir -p $(RESULTS)/parser-comments
	@echo "=============================="
	@echo " php-parser: コメント差分 (コメントあり)"
	@echo "=============================="
	@for v in $(VERSIONS); do \
		$(COMPOSE) run --rm $$v php dump_parser.php cases/comment_diff.php 2>/dev/null \
			| tee $(RESULTS)/parser-comments/$$v.txt; \
		echo ""; \
	done
	@echo "=> $(RESULTS)/parser-comments/ に保存しました"

# --- nikic/php-parser: コメント差分 (コメントなし) ---
parser-comments-no-comments:
	@mkdir -p $(RESULTS)/parser-comments-no-comments
	@echo "=============================="
	@echo " php-parser: コメントなしコード (比較用)"
	@echo "=============================="
	@for v in $(VERSIONS); do \
		$(COMPOSE) run --rm $$v php dump_parser.php cases/comment_diff_no_comments.php 2>/dev/null \
			| tee $(RESULTS)/parser-comments-no-comments/$$v.txt; \
		echo ""; \
	done
	@echo "=> $(RESULTS)/parser-comments-no-comments/ に保存しました"

# --- nikic/php-parser: 変数埋め込み ---
parser-interpolation:
	@mkdir -p $(RESULTS)/parser-interpolation
	@echo "=============================="
	@echo " php-parser: 変数埋め込み (deprecated interpolation)"
	@echo "=============================="
	@for v in $(VERSIONS); do \
		$(COMPOSE) run --rm $$v php dump_parser.php cases/deprecated_interpolation.php 2>/dev/null \
			| tee $(RESULTS)/parser-interpolation/$$v.txt; \
		echo ""; \
	done
	@echo "=> $(RESULTS)/parser-interpolation/ に保存しました"

# --- nikic/php-parser: DocBlock 変数埋め込み ---
parser-docblock-interpolation:
	@mkdir -p $(RESULTS)/parser-docblock-interpolation
	@echo "=============================="
	@echo " php-parser: DocBlock 変数埋め込み"
	@echo "=============================="
	@for v in $(VERSIONS); do \
		$(COMPOSE) run --rm $$v php dump_parser.php cases/docblock_interpolation.php 2>/dev/null \
			| tee $(RESULTS)/parser-docblock-interpolation/$$v.txt; \
		echo ""; \
	done
	@echo "=> $(RESULTS)/parser-docblock-interpolation/ に保存しました"

# --- AST ハッシュ比較 ---
hash-compare:
	@mkdir -p $(RESULTS)/hash-compare
	@echo "=============================="
	@echo " AST ハッシュ比較 (${var} -> {$var} 置換)"
	@echo "=============================="
	@for v in $(VERSIONS); do \
		$(COMPOSE) run --rm $$v php hash_compare.php 2>/dev/null \
			| tee $(RESULTS)/hash-compare/$$v.txt; \
		echo ""; \
	done
	@echo "=> $(RESULTS)/hash-compare/ に保存しました"

# --- 単一バージョンで実行 ---
# 使い方: make run-one VER=php85 FILE=cases/deprecated_interpolation.php
run-one:
	$(COMPOSE) run --rm $(VER) php dump_ast.php $(FILE) $(OPTS)

# 結果ファイルを削除
clean-results:
	rm -rf $(RESULTS)

# クリーンアップ
clean:
	$(COMPOSE) down --rmi local
