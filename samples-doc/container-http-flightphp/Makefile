SHELL:=/bin/bash
TARGET_PATH=target
DOCKER_FILE=Dockerfile

IMAGE_NAME=custom_container_http_flightphp_php

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


run_local:
	php -S 0.0.0.0:8000 -t src

#############################################################################################
# Test targets
#############################################################################################

test_local_get_search:
	# execute a curl request against the local function
	curl -X GET \
	   -H 'Content-Type: application/json' \
		 http://localhost:8000/search?name=John
	@echo ""

test_local_post_json:
	# execute a curl request against the local function
	curl -X POST \
	   -H 'Content-Type: application/json' \
		 -d @./resources/curl_apig_post_index.json \
		 http://localhost:8000/json
	@echo ""


.PHONY: docker_build docker_run_local docker_stop_local docker_push docker_all test_local test_local_get_search test_local_post_json
