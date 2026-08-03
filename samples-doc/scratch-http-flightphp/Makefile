# Terraform backend configuration
BACKEND_CONFIG_BUCKET := "doc-samples-tf-backend"
BACKEND_CONFIG_KEY := "terraform_state/php/scratch-http-flightphp.tf"
BACKEND_CONFIG_REGION := "eu-de"
BACKEND_CONFIG_ENDPOINTS := "endpoints={s3=\"https://obs.eu-de.otc.t-systems.com\"}"

CURRENT_MAKEFILE := $(firstword $(MAKEFILE_LIST))

# create a zip package of the function code for deployment
# based on composer.json archive settings
create_package:
	composer archive --format=zip --file=code

tf_init:
	terraform -chdir=terraform \
	  init \
	  -backend-config=$(BACKEND_CONFIG_ENDPOINTS) \
	  -backend-config="bucket=$(BACKEND_CONFIG_BUCKET)" \
	  -backend-config="key=$(BACKEND_CONFIG_KEY)" \
	  -backend-config="region=$(BACKEND_CONFIG_REGION)"

tf_plan:
	if [ ! -f "terraform/.terraform.lock.hcl" ]; then \
		$(MAKE) -f $(CURRENT_MAKEFILE) tf_init; \
	fi
	terraform -chdir=terraform \
	  plan \
	  -var-file="variables.tfvars"

tf_apply: create_package
	if [ ! -f "terraform/.terraform.lock.hcl" ]; then \
		$(MAKE) -f $(CURRENT_MAKEFILE) tf_init; \
	fi
	terraform -chdir=terraform \
	  apply -auto-approve \
	  -var-file="variables.tfvars"

tf_destroy:
	terraform -chdir=terraform \
	  destroy -auto-approve \
	  -var-file="variables.tfvars"

######################################################################

# run flightphp server locally for testing
run_local:
	php -S localhost:8000 -t src

# test the function locally via flightphp server
test_local:
	curl -i -H 'X-Cff-Request-Id: req-123' http://localhost:8000/json

test_deployed_apig_get_json:
	# get the API Gateway URL from terraform output
	$(eval API_GATEWAY_TRIGGER_URL := $(shell terraform -chdir=terraform output -raw API_GATEWAY_TRIGGER_URL))
	@echo "Using API Gateway URL: $(API_GATEWAY_TRIGGER_URL)"
	# execute a curl request against the deployed function via API Gateway
	curl -X GET \
	   -H 'Content-Type: application/json' \
	   $(API_GATEWAY_TRIGGER_URL)/json

.PHONY: create_package tf_init tf_plan tf_apply tf_destroy test_deployed_apig_get_json run_local test_local