SRC_FILES = $(shell find example src -type f -name '*.php')

.PHONY: test
test: cs
	vendor/bin/phpunit
	vendor/bin/phpstan analyse --memory-limit 2g

.PHONY: cs
cs:
	vendor/bin/phpcs

.PHONY: cbf
cbf:
	vendor/bin/phpcbf

.PHONY: fix
fix: cbf
	vendor/bin/php-cs-fixer fix
