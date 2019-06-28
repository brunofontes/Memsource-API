install:
	command -v composer && composer install || echo "Please, install Composer to use tests"
	command -v phpunit || command -v composer && composer require --dev phpunit/phpunit ^8
	chmod +x ./pre-push.sh
	mkdir -p .git/hooks
	ln -fs ../../pre-push.sh .git/hooks/pre-push

uninstall:
	rm -f ./.git/hooks/pre-push
	if [ -d "./vendor/phpunit" ] ; then composer remove --dev phpunit/phpunit; fi
