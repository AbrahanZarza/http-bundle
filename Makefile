IMAGE_NAME=http-bundle
IMAGE_TAG=dev
RUN_COMMAND=docker run --rm -v ${PWD}:/app -w /app $(IMAGE_NAME):$(IMAGE_TAG)
COMPOSER_COMMAND=$(RUN_COMMAND) composer

build-container:
	@docker build . -t $(IMAGE_NAME):$(IMAGE_TAG)

composer-install:
	@$(COMPOSER_COMMAND) install --verbose

composer-update:
	@$(COMPOSER_COMMAND) update --verbose

build: build-container composer-install

test-coverage:
	@$(RUN_COMMAND) php -dxdebug.mode=coverage ./vendor/bin/phpunit --color=always

test-group:
	@$(RUN_COMMAND) php ./vendor/bin/phpunit --no-coverage --color=always --group ${GROUP}
