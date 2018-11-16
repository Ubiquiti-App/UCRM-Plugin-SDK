default:
	composer dump
	./vendor/bin/ecs check src tests
	./vendor/bin/phpstan analyse src tests --level max
	./vendor/bin/phpunit

csfix:
	./vendor/bin/ecs check src tests --fix

test_coverage:
	./vendor/bin/phpunit --coverage-html coverage
