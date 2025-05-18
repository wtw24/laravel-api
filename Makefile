check: pint-dry-run rector-dry-run phpstan
format: pint rector pint-again phpstan

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
