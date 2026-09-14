# create a zip package of the function code for deployment
# based on composer.json archive settings
create_package:
	# install production dependencies without development dependencies
	composer install --no-dev --prefer-dist
	# create a code.zip archive of the production dependencies
	composer archive --format=zip --file=code
	# reinstall all dependencies including development dependencies
	composer install

.PHONY: create_package