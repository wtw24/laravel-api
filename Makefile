check: pint-dry-run rector-dry-run phpstan test
format: pint rector pint-again phpstan test
test: clear-config test-architecture test-unit test-feature

rector:
	docker compose run --rm php-cli composer run rector

rector-dry-run:
	docker compose run --rm php-cli composer run rector-dry-run

phpstan:
	docker compose run --rm php-cli composer run phpstan

pint:
	docker compose run --rm php-cli composer run pint

pint-again:
	docker compose run --rm php-cli composer run pint

pint-dirty:
	docker compose run --rm php-cli composer run pint -- --dirty

pint-dry-run:
	docker compose run --rm php-cli composer run pint -- --test --verbose

clear-config:
	docker compose run --rm php-cli php artisan config:clear --ansi

test-architecture:
	docker compose run --rm php-cli composer run test -- --testdox --testdox-summary --testsuite=Architecture

test-unit:
	docker compose run --rm php-cli composer run test -- --testdox --testdox-summary --testsuite=Unit

test-feature:
	docker compose run --rm php-cli composer run test -- --testdox --testdox-summary --testsuite=Feature
