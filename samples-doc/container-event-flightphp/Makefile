SHELL:=/bin/bash
TARGET_PATH=target
DOCKER_FILE=Dockerfile

IMAGE_NAME=custom_container_event_flightphp_php

docker_build: 
	docker buildx build \
	  --platform linux/amd64 \
		--build-arg DOCKER_BUILD_TIMESTAMP="$$(date -u +%Y-%m-%dT%H:%M:%SZ)" \
		--file $(DOCKER_FILE) \
		--tag $(IMAGE_NAME):latest .

docker_push: docker_build
  # see: https://docs.otc.t-systems.com/software-repository-container/umn/image_management/obtaining_a_long-term_valid_login_command.html#swr-01-1000
	@echo $(OTC_SWR_LOGIN_KEY) | docker login -u $(OTC_SDK_PROJECTNAME)@$(OTC_SDK_AK) --password-stdin $(OTC_SWR_ENDPOINT)
	docker tag $(IMAGE_NAME):latest $(OTC_SWR_ENDPOINT)/$(OTC_SWR_ORGANIZATION)/$(IMAGE_NAME):latest
	docker push $(OTC_SWR_ENDPOINT)/$(OTC_SWR_ORGANIZATION)/$(IMAGE_NAME):latest

docker_all: docker_build docker_push

############################################################################
docker_run_local:
	-docker rm -f $(IMAGE_NAME)
	docker container run \
	  -d \
	  --rm \
		--platform linux/amd64 \
		--publish 8000:8000 \
		--user 1003:1003 \
		--name $(IMAGE_NAME) \
		$(IMAGE_NAME):latest

docker_stop_local:
	-docker rm -f $(IMAGE_NAME)

test_local:
  # execute a curl 
	curl -X POST \
	-H "X-Cff-Request-Id: $(shell uuidgen)" \
	-H "X-Cff-Func-Name: 0@default@sample-container-event-flightphp" \
	-H "X-Cff-Func-Version: latest" \
	-H "X-Cff-Func-Timeout: 30" \
	-H "X-Cff-region: eu_de" \
	-H 'Content-Type: application/json' \
	-d @'./resources/test_event.json' \
	 localhost:8000/invoke
	@echo ""


.PHONY: docker_build docker_run_local docker_stop_local docker_push docker_all test_local 
