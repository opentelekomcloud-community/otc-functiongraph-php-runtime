# Terraform backend configuration
BACKEND_CONFIG_BUCKET := "doc-samples-tf-backend"
BACKEND_CONFIG_KEY := "terraform_state/php/scratch-event-timer.tf"
BACKEND_CONFIG_REGION := "eu-de"
BACKEND_CONFIG_ENDPOINTS := "endpoints={s3=\"https://obs.eu-de.otc.t-systems.com\"}"

CURRENT_MAKEFILE := $(firstword $(MAKEFILE_LIST))

# IAC_TOOL can be set to "terraform" or "tofu" depending on which tool
# you want to use for deployment.
# Default is "tofu".
IAC_TOOL := terraform


# create a zip package of the function code for deployment
# based on composer.json archive settings
create_package:
	composer update
	composer archive --format=zip --file=code

tf_init:
	$(IAC_TOOL) -chdir=terraform \
	  init \
	  -backend-config=$(BACKEND_CONFIG_ENDPOINTS) \
	  -backend-config="bucket=$(BACKEND_CONFIG_BUCKET)" \
	  -backend-config="key=$(BACKEND_CONFIG_KEY)" \
	  -backend-config="region=$(BACKEND_CONFIG_REGION)"

tf_plan:
	if [ ! -f "terraform/.terraform.lock.hcl" ]; then \
		$(MAKE) -f $(CURRENT_MAKEFILE) tf_init; \
	fi
	$(IAC_TOOL) -chdir=terraform \
	  plan \
	  -var-file="variables.tfvars"

tf_apply: create_package
	if [ ! -f "terraform/.terraform.lock.hcl" ]; then \
		$(MAKE) -f $(CURRENT_MAKEFILE) tf_init; \
	fi
	$(IAC_TOOL) -chdir=terraform \
	  apply -auto-approve \
	  -var-file="variables.tfvars"

tf_destroy:
	$(IAC_TOOL) -chdir=terraform \
	  destroy -auto-approve \
	  -var-file="variables.tfvars"

#####################################################################

test_deployed:
  # getting Token for authentication from Username/Password...
	$(eval OTC_X_AUTH_TOKEN := $(shell ../../utils/tokenFromUsername.sh))
	# getting the Function URN from terraform output...
	$(eval MY_FUNCTION_URN := $(shell terraform -chdir=terraform output -raw MY_FUNCTION_URN))
	# calling the deployed function via FunctionGraph API...
	@curl -X POST \
	 -H "Content-Type: application/json" \
	 -H "x-auth-token: $(OTC_X_AUTH_TOKEN)" \
	 -d @./resources/test_event.json \
	 https://functiongraph.$(OTC_SDK_REGION).otc.t-systems.com/v2/$(OTC_SDK_PROJECTID)/fgs/functions/$(MY_FUNCTION_URN):latest/invocations
	@echo "" 
	# finished


.PHONY: create_package tf_init tf_plan tf_apply tf_destroy test_deployed
