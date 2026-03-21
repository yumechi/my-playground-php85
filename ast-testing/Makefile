COMPOSE := podman compose
VERSIONS := php81 php82 php83 php84 php85

.PHONY: build all interpolation comments comments-with clean

# 全イメージをビルド
build:
	$(COMPOSE) build

# 全ケースを全バージョンで実行
all: interpolation comments comments-with comments-no-comments

# --- 変数埋め込み deprecated 検証 ---
interpolation:
	@echo "=============================="
	@echo " 変数埋め込み (deprecated interpolation)"
	@echo "=============================="
	@for v in $(VERSIONS); do \
		$(COMPOSE) run --rm $$v php dump_ast.php cases/deprecated_interpolation.php 2>/dev/null; \
		echo ""; \
	done

# --- コメント差分検証 (コメントオプションなし) ---
comments:
	@echo "=============================="
	@echo " コメント差分 (WITHOUT --with-comments)"
	@echo "=============================="
	@for v in $(VERSIONS); do \
		$(COMPOSE) run --rm $$v php dump_ast.php cases/comment_diff.php 2>/dev/null; \
		echo ""; \
	done

# --- コメント差分検証 (コメントオプションあり) ---
comments-with:
	@echo "=============================="
	@echo " コメント差分 (WITH --with-comments)"
	@echo "=============================="
	@for v in $(VERSIONS); do \
		$(COMPOSE) run --rm $$v php dump_ast.php cases/comment_diff.php --with-comments 2>/dev/null; \
		echo ""; \
	done

# --- コメントなしコード (比較用) ---
comments-no-comments:
	@echo "=============================="
	@echo " コメントなしコード (比較用)"
	@echo "=============================="
	@for v in $(VERSIONS); do \
		$(COMPOSE) run --rm $$v php dump_ast.php cases/comment_diff_no_comments.php 2>/dev/null; \
		echo ""; \
	done

# --- 単一バージョンで実行 ---
# 使い方: make run-one VER=php85 FILE=cases/deprecated_interpolation.php [OPTS=--with-comments]
run-one:
	$(COMPOSE) run --rm $(VER) php dump_ast.php $(FILE) $(OPTS)

# クリーンアップ
clean:
	$(COMPOSE) down --rmi local
