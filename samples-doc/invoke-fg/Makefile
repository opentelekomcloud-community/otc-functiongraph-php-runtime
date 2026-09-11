composer:
	COMPOSER=composer.json composer update

composer_curl:
	COMPOSER=composer_curl.json composer update

run_sync_curl: composer_curl	
	php src/invokeSync_AKSK_curl.php

run_async_curl: composer_curl	
	php src/invokeASync_AKSK_curl.php

run_sync: composer
	COMPOSER=composer.json composer update
	php src/invokeSync_AKSK.php

run_async: composer
	COMPOSER=composer.json composer update
	php src/invokeASync_AKSK.php

.PHONY: composer composer_curl run_sync_curl run_async_curl run_sync run_async