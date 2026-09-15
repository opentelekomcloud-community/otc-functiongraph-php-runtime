Invoke FunctionGraph Function from FunctionGraph using Token
===================================================================

.. toctree::
   :maxdepth: 1
   :hidden:


This page demonstrates how to call a FunctionGraph implemented
in PHP from another FunctionGraph function using API calls and
**token** provided by an agency of `Agency Type` **Cloud Service** 
for `Cloud Service` **FunctionGraph Service** with permission to invoke FunctionGraph.
for authentication.

See: :ref:`invoke_functiongraph_function_api` for more details on
how to use the REST API.

Prerequisites
-----------------

1. URN of Function to be called.
   In this example the code of the function to be called is:

   .. literalinclude:: ../../../../samples-doc/invoke-fg2fg/src-fg/index.php
      :language: php
      :caption: :github_repo_master:`samples-doc/invoke-fg2fg/src-fg/index.php <samples-doc/invoke-fg2fg/src-fg/index.php>`

   .. note::
      Ensure that the function and the subfunction are created in the same region.   

2. An agency of `Agency Type` **Cloud Service** for `Cloud Service` **FunctionGraph Service**
   with permission to invoke FunctionGraph.

   The permission policy should contain following policy statement:

   .. code-block:: json

      {
        "Version": "1.1",
        "Statement": [
          {
            "Action": [
              "functiongraph:function:invokeAsync*",
              "functiongraph:function:invoke"
              ],
            "Effect": "Allow"
          }
        ]
      }

   or use an agency with default permission **FunctionGraph CommonOperations**.

   .. note::
      The permissions shown above are for demonstration purpose.
      Please follow the principle of least privilege when creating 
      the permission policy for the agency.

      e.g. to grant permission to invoke only specific functions,
      the policy statement should be like:

      .. code-block:: json

         {
           "Version": "1.1",
           "Statement": [
             {
               "Action": [
                 "functiongraph:function:invokeAsync*",
                 "functiongraph:function:invoke"
                 ],
               "Effect": "Allow",
               "Resource": [
                 "RESOURCE_PATH"           
               ]
             }
           ]
         }

      where **"RESOURCE_PATH"** is in format

      .. code-block:: text

          FunctionGraph:::function:group/function name

      By adding Function name to the end of the generated prefix,
      you can define a specific path.
      
      An asterisk * is allowed to indicate any function.
      
      For example, **FunctionGraph:*:*:function:default/*** indicates
      any function in the **default** group.

      For more details, see :docs_otc:`Policy Syntax<identity-access-management/umn/user_guide/permissions/policy_syntax.html>` in Identity and Access Management User Guide.

      (Remark: changing the permission policy may take some time to take effect.)


Coding
---------------------

.. tabs::
  
    .. tab:: Code using PHP "Guzzle"

      Create a function with following content to call another FunctionGraph function:

      .. literalinclude:: ../../../../samples-doc/invoke-fg2fg/guzzle_token/src/index.php
        :language: php
        :caption: :github_repo_master:`samples-doc/invoke-fg2fg/guzzle_token/src/index.php <samples-doc/invoke-fg2fg/guzzle_token/src/index.php>`
        :tab-width: 2

      Create a composer.json file with following content:  

      .. literalinclude:: ../../../../samples-doc/invoke-fg2fg/guzzle_token/composer.json
        :language: json
        :caption: :github_repo_master:`samples-doc/invoke-fg2fg/guzzle_token/composer.json <samples-doc/invoke-fg2fg/guzzle_token/composer.json>`

      Create a makefile with following content:

      .. literalinclude:: ../../../../samples-doc/invoke-fg2fg/guzzle_token/Makefile
        :language: make
        :caption: :github_repo_master:`samples-doc/invoke-fg2fg/guzzle_token/Makefile <samples-doc/invoke-fg2fg/guzzle_token/Makefile>`
        :tab-width: 2

    .. tab:: Code using PHP "cURL"

      Create a function with following content to call another FunctionGraph function:

      .. literalinclude:: ../../../../samples-doc/invoke-fg2fg/curl_token/src/index.php
        :language: php
        :caption: :github_repo_master:`samples-doc/invoke-fg2fg/curl_token/src/index.php <samples-doc/invoke-fg2fg/curl_token/src/index.php>`
        :tab-width: 2

      Create a composer.json file with following content:

      .. literalinclude:: ../../../../samples-doc/invoke-fg2fg/curl_token/composer.json
        :language: json
        :caption: :github_repo_master:`samples-doc/invoke-fg2fg/curl_token/composer.json <samples-doc/invoke-fg2fg/curl_token/composer.json>`  

      Create a makefile with following content:

      .. literalinclude:: ../../../../samples-doc/invoke-fg2fg/curl_token/Makefile
        :language: make
        :caption: :github_repo_master:`samples-doc/invoke-fg2fg/curl_token/Makefile <samples-doc/invoke-fg2fg/curl_token/Makefile>`
        :tab-width: 2

Deployment
---------------------

Create a deployment package using **make create_package** command
and deploy the package to FunctionGraph using the console as an event
function from scratch using PHP 8.3.

Configure the function:

- set the handler name as **src/index.handler**.
- specify an agency with permission to **invoke** FunctionGraph
- and set the URN of the function to be called as environment variable
  with key **CALL_FG_URN**.

Testing
----------

Create a test event based on Blank Template and click **Test**. 

Execution Result on the right should show a successful execution and the function
set in the **CALL_FG_URN** environment variable should have a new invoke request in its Monitoring.
