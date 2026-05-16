.PHONY: help rodar parar rebuild logs status git-status push

BRANCH := $(shell git rev-parse --abbrev-ref HEAD)
MSG ?= chore: update

help:
	@echo "Alvos disponiveis:"
	@echo "  make rodar              - sobe o projeto com Docker"
	@echo "  make parar              - para os containers"
	@echo "  make rebuild            - recria os containers do zero"
	@echo "  make logs               - mostra logs em tempo real"
	@echo "  make status             - mostra status dos containers"
	@echo "  make git-status         - mostra status do git"
	@echo "  make push MSG=\"msg\"    - add + commit + push direto"

rodar:
	docker compose up -d --build

parar:
	docker compose down

rebuild:
	docker compose down -v
	docker compose up -d --build

logs:
	docker compose logs -f --tail=200

status:
	docker compose ps

git-status:
	git status -sb

push:
	git add -A
	@if git diff --cached --quiet; then \
		echo "Sem alteracoes para commit."; \
	else \
		git commit -m "$(MSG)"; \
	fi
	git push origin $(BRANCH)
