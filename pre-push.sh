params="--bootstrap vendor/autoload.php tests --testdox --color"

if [ $(command -v phpunit) ]; then
    phpunit $params
else
    if [ -d ./vendor/phpunit ]; then
        ./vendor/phpunit/phpunit/phpunit $params
    else
        echo "Please, run 'make install' or install phpunit globally to run the tests"
    fi
fi
