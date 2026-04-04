.PHONY: migration fixtures

migration:
	php bin/console doctrine:migrations:migrate --no-interaction

fixtures:
	# Fixtures dev
	php bin/console doctrine:fixtures:load --group=dev --no-interaction

	# Fixtures test dans la base test
	php bin/console --env=test doctrine:fixtures:load --group=test --no-interaction