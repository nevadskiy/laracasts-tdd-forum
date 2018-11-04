Up: 
```
UID=${UID} GID=${GID} docker-compose up -d
```

Or, export these variables as environment variables, ignoring bash: UID: readonly variable
```
export UID=${UID}
export GID=${GID} 
```

ALIASES:
```
alias tf='docker-compose exec php-cli vendor/bin/phpunit --filter'
alias art='docker-compose exec php-cli php artisan'
```
