install:
	composer update
	chmod +x ./pre-push.sh
	mkdir -p .git/hooks
	ln -fs ../../pre-push.sh .git/hooks/pre-push

uninstall:
	rm -f ./.git/hooks/pre-push
