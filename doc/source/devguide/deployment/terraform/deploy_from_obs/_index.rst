.. _ref_deploy_from_obs:

Deploy FunctionGraph Event Function from OBS
=============================================

.. toctree::
   :maxdepth: 2
   :hidden:

This sample demonstrates how to deploy a simple event function to 
FunctionGraph with **code from OBS** using terraform.

This approach is used, if your unpacked FunctionGraph deployment package is **less than 40MB**.

Source code of this sample is available on :github_repo_master:`GitHub <samples-doc/deploy-from-obs>`.

Prerequisites
-----------------

- running on Linux / Windows Subsystem for Linux (WSL)
- make installed
- curl installed
- Terraform installed and configured, see :ref:`Terraform Setup<ref_terraform_setup>`.

What will be deployed
----------------------------------

- obs bucket and obs bucket object for function code
- event function
- lts log group and log stream
- test event configuration

Creating deployment package
----------------------------------

The deployment package for this sample is created with ``make create_package``.

.. literalinclude:: /../../samples-doc/deploy-from-obs/Makefile
  :language: make
  :caption: :github_repo_master:`Makefile <samples-doc/deploy-from-obs/Makefile>`
  :tab-width: 2

This will create a ``code.zip`` file with the function code in the project root  folder.


Terraform files
-------------------------
The terraform files for this sample are located in the ``samples-doc/deploy-from-obs/terraform`` folder:

provider.tf
^^^^^^^^^^^^
The file  ``provider.tf`` defines the provider configuration for this sample:

.. literalinclude:: /../../samples-doc/deploy-from-obs/terraform/provider.tf
  :language: hcl
  :caption: :github_repo_master:`provider.tf <samples-doc/deploy-from-obs/terraform/provider.tf>`
  :tab-width: 2

You might need to adapt the provider configuration to your needs,
especially the provider version and backend configuration for terraform state.

For variables used in provider.tf, see :ref:`Terraform Setup<ref_terraform_setup>`.
  

variables.tf
^^^^^^^^^^^^
The file ``variables.tf`` defines the variables for this sample:

.. literalinclude:: /../../samples-doc/deploy-from-obs/terraform/variables.tf
  :language: hcl
  :caption: :github_repo_master:`variables.tf <samples-doc/deploy-from-obs/terraform/variables.tf>`
  :tab-width: 2

These variables will set in the ``variables.tfvars`` file.

code_from_obs_bucket.tf
^^^^^^^^^^^^^^^^^^^^^^^^

The file  ``code_from_obs_bucket.tf`` defines the obs bucket and obs bucket object resource

.. literalinclude:: /../../samples-doc/deploy-from-obs/terraform/code_from_obs_bucket.tf
  :language: hcl
  :caption: :github_repo_master:`code_from_obs_bucket.tf <samples-doc/deploy-from-obs/terraform/code_from_obs_bucket.tf>`
  :tab-width: 2


function.tf
^^^^^^^^^^^^
The file  ``function.tf`` defines the function resource for this sample:

.. literalinclude:: /../../samples-doc/deploy-from-obs/terraform/function.tf
  :language: hcl
  :caption: :github_repo_master:`function.tf <samples-doc/deploy-from-obs/terraform/function.tf>`
  :tab-width: 2

The relevant part for deploying function code from zip file is:

.. code-block:: hcl

  ###### relevant part for deploy function code from obs file ######
  code_type = "obs"
  code_url = format("https://%s/%s/%s",
    opentelekomcloud_obs_bucket.codebucket.bucket_domain_name,
    "code",
    basename(var.zip_file_name)
  )

  # on change of the code object etag (hash) new code  version will be deployed.
  source_code_hash = opentelekomcloud_obs_bucket_object.code_object.etag
  ###### ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ ######


loggroup.tf
^^^^^^^^^^^^
The file  ``loggroup.tf`` defines the log group and log stream for this sample:

.. literalinclude:: /../../samples-doc/deploy-from-obs/terraform/loggroup.tf
  :language: hcl
  :caption: :github_repo_master:`loggroup.tf <samples-doc/deploy-from-obs/terraform/loggroup.tf>`
  :tab-width: 2

testevent.tf
^^^^^^^^^^^^
The file  ``testevent.tf`` creates a test event configuration to be used in the
FunctionGraph console for testing the deployed function.

.. literalinclude:: /../../samples-doc/deploy-from-obs/terraform/testevent.tf
  :language: hcl
  :caption: :github_repo_master:`testevent.tf <samples-doc/deploy-from-obs/terraform/testevent.tf>`
  :tab-width: 2

The test event will have the following content:

.. literalinclude:: /../../samples-doc/deploy-from-obs/resources/test_event.json
  :language: json
  :caption: :github_repo_master:`resources/test_event.json <samples-doc/deploy-from-obs/resources/test_event.json>`
  :tab-width: 2

Deploying using Terraform and make
----------------------------------

Following makefile can be used to deploy the function to FunctionGraph using Terraform.

.. literalinclude:: /../../samples-doc/deploy-from-obs/Makefile
  :language: make
  :caption: :github_repo_master:`Makefile <samples-doc/deploy-from-obs/Makefile>`
  :tab-width: 2

Makefile targets:

- ``create_package``: creates the deployment package as zip file using ```composer archive```.
- ``tf_init``: initializes terraform, this will create the terraform state file in the defined backend.
- ``tf_plan``: runs terraform plan to see which changes will be applied.
- ``tf_apply``: runs terraform apply to deploy the function to FunctionGraph.
- ``tf_destroy``: runs terraform destroy to remove the deployed infrastructure.


Adaptions
^^^^^^^^^^^^

Adaptions in Makefile
""""""""""""""""""""""""""""
Before running the targets in the makefile,
make sure to 

Adapt the **BACKEND_CONFIG_*** variables in the Makefile

.. list-table:: Backend config variables
  :header-rows: 1

  * - Variable
    - Description
    - Example value
  * - BACKEND_CONFIG_BUCKET
    - The name of the bucket where terraform state is stored
    - doc-samples-tf-backend
  * - BACKEND_CONFIG_KEY
    - The name of the object(key) where terraform state is stored
    - terraform_state/php/deploy-from-obs.tf
  * - BACKEND_CONFIG_REGION
    - The region where the bucket for terraform state is stored is located
    - eu-de
  * - BACKEND_CONFIG_ENDPOINTS
    - The OBS endpoints for the bucket where terraform state is stored
    - endpoints={s3=\"https://obs.eu-de.otc.t-systems.com\"}
    

Adaptions in variables.tfvars
""""""""""""""""""""""""""""""  
Adapt the variables in the ``variables.tfvars`` 

.. literalinclude:: /../../samples-doc/deploy-from-obs/terraform/variables.tfvars
  :language: hcl
  :caption: :github_repo_master:`variables.tfvars <samples-doc/deploy-from-obs/terraform/variables.tfvars>`
  :tab-width: 2

.. list-table:: Backend config variables
  :header-rows: 1

  * - Variable
    - Description
    - Example value
  * - prefix
    - The prefix for the generated resources, all generated resources
      will have this prefix in their name.     
    - php
  * - description
    - The description for the deployed function.
    - Sample deploy-from-obs 
  * - function_name
    - The name of the deployed function (will be prefixed).
    - deploy-from-obs
  * - handler_name
    - The handler name for the deployed 
    - src/index.handler
  * - initializer_name
    - The initializer name for the deployed function.
    - src/index.initializer
  * - zip_file_name
    - The name (with relative path) of the zip file with the function
      code which is created by ```composer archive```.
    - code.zip
  * - tag_app_group
    - The tag "app_group" with this value will be added to all
      created resources, where tagging is applicable. 
    - deploy-from-obs

Deployment to Cloud
^^^^^^^^^^^^^^^^^^^^
After the necessary adaptions are done, you can run the following command to deploy the function to
FunctionGraph:

.. code-block:: bash

   make tf_apply

Testing the deployed function
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

For testing the deployed function, you will need to set the following
environment variables. These are used in "tokenFromUsername.sh" script to get Token
for Token-based authentication when calling FunctionGraph API.

.. list-table:: Environment Variables
   :widths: 25 25
   :header-rows: 1

   * - Name
     - Description
   * - OTC_USER_NAME
     - User name
   * - OTC_USER_PASSWORD
     - User password

Synchronous invocation
"""""""""""""""""""""""""""""
After the deployment is done, you can test the deployed function with the following command:

.. code-block:: bash

   make test_deployed

This will call the deployed function with a test event in synchronous way and print the response.

.. code-block:: bash

  # getting the Function URN from terraform output...
  # calling the deployed function via FunctionGraph API...
  {"statusCode":200,"headers":{"Content-Type":"application/json"},"isBase64Encoded":false,"body":"{\"key\":\"Hello World of FunctionGraph\"}"}
  # finished

Destroy all deployed resources from Cloud
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

To destroy all deployed resources from the cloud, you can run the following command:

.. code-block:: bash

   make tf_destroy
