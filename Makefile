.PHONY: run

FILE ?= solution.php

run:
	podman compose run --rm php php $(FILE)
