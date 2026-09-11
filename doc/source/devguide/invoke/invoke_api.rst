.. _invoke_functiongraph_function_api:

Invoking FunctionGraph Event Function using API Calls
======================================================

This section demonstrates how to call a FunctionGraph implemented in
PHP using API calls and AK/SK-based authentication.

For details on AK/SK-based authentication, see

* :docs_otc:`AK/SK-based Authentication <identity-access-management/api-ref/calling_apis/authentication.html#ak-sk-based-authentication>`

in `Identity and Access Management (IAM)` API reference.

Prerequisites
-------------

For details on prerequisites, see: :ref:`ref_invoke-prerequisites`

Using AK/SK authentication for API calls
-----------------------------------------

Using AK/SK authentication the
requests have to be signed with the AK/SK (or for temporal credentials with SecurityAccessKey/SecurityKey/SecurityToken).

For request signing the :github_otc_community:`otc-api-sign-sdk-php <otc-api-sign-sdk-php>` can be used.

The SDK provides a method to sign the request with AK/SK or SecurityAccessKey/SecurityKey/SecurityToken.

Additional to the environment variables mentioned in the prerequisites,
you also need to set the following environment variables
for AK/SK authentication:

  - OTC_SDK_AK (Access Key with permission to invoke FunctionGraph)
  - OTC_SDK_SK (Secret Key corresponding to the Access Key)

Synchronous invocation using AK/SK
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Following sample code demonstrates how to invoke a FunctionGraph event function synchronously using API calls with AK/SK authentication.

See :otc_fg_api:`Executing a Function Synchronously <api/function_invocation/executing_a_function_synchronously.html#functiongraph-06-0125>`
in FunctionGraph API reference for more details about synchronous invocation.

.. tabs::
  
    .. tab:: Code using PHP "Guzzle"

      .. literalinclude:: ../../../../samples-doc/invoke-fg/guzzle/src/invokeSync_AKSK.php
        :language: php
        :caption: :github_repo_master:`samples-doc/invoke-fg/guzzle/src/invokeSync_AKSK.php <samples-doc/invoke-fg/guzzle/src/invokeSync_AKSK.php>`
        :tab-width: 2

      .. literalinclude:: ../../../../samples-doc/invoke-fg/guzzle/composer.json
        :language: php
        :caption: :github_repo_master:`samples-doc/invoke-fg/guzzle/composer.json <samples-doc/invoke-fg/guzzle/composer.json>`
        :tab-width: 2  

      To execute the sample code, run the following command in the terminal
      in folder ``samples-doc/invoke-fg/guzzle``:

      .. code-block:: bash

        php src/invokeSync_AKSK.php

    .. tab:: Code using PHP "curl"

      .. literalinclude:: ../../../../samples-doc/invoke-fg/curl/src/invokeSync_AKSK.php
        :language: php
        :caption: :github_repo_master:`samples-doc/invoke-fg/curl/src/invokeSync_AKSK.php <samples-doc/invoke-fg/curl/src/invokeSync_AKSK.php>`
        :tab-width: 2

      .. literalinclude:: ../../../../samples-doc/invoke-fg/curl/composer.json
        :language: php
        :caption: :github_repo_master:`samples-doc/invoke-fg/curl/composer.json <samples-doc/invoke-fg/curl/composer.json>`
        :tab-width: 2   

      To execute the sample code, run the following command in the terminal
      in folder ``samples-doc/invoke-fg/curl``:

      .. code-block:: bash

        php src/invokeSync_AKSK.php

    You should see an output similar to the following in the terminal:

.. code-block:: bash
  :caption: Sample output of synchronous invocation using AK/SK

  Result:  {"statusCode":200,"headers":{"Content-Type":"application/json"},"isBase64Encoded":false,"body":"{\"key\":\"Hello World\"}"}
  Log:  2026-03-17T10:13:22Z Start invoke request '960d089c-c812-4fe7-9997-a72f773e4bcb', version: latest
  2026-03-17T10:13:22Z 960d089c-c812-4fe7-9997-a72f773e4bcb INFO Function name: php-sample-invoke-function
  2026-03-17T10:13:22Z 960d089c-c812-4fe7-9997-a72f773e4bcb INFO Key value from event: Hello World
  2026-03-17T10:13:22Z Finish invoke request '960d089c-c812-4fe7-9997-a72f773e4bcb', duration: 2.049ms, billing duration: 3ms, memory used: 36.535MB, billing memory: 128MB, cpu used: 0.300U, storage used: 0.039MB


Asynchronous invocation using AK/SK
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Following sample code demonstrates how to invoke a FunctionGraph event function asynchronously using API calls with AK/SK authentication.

See :otc_fg_api:`Executing a Function Asynchronously <api/function_invocation/executing_a_function_asynchronously.html#functiongraph-06-0126>`
in FunctionGraph API reference for more details about asynchronous invocation.


.. tabs::
  
    .. tab:: Code using PHP "Guzzle"

      .. literalinclude:: ../../../../samples-doc/invoke-fg/guzzle/src/invokeASync_AKSK.php
        :language: php
        :caption: :github_repo_master:`samples-doc/invoke-fg/guzzle/src/invokeASync_AKSK.php <samples-doc/invoke-fg/guzzle/src/invokeASync_AKSK.php>`
        :tab-width: 2

      .. literalinclude:: ../../../../samples-doc/invoke-fg/guzzle/composer.json
        :language: php
        :caption: :github_repo_master:`samples-doc/invoke-fg/guzzle/composer.json <samples-doc/invoke-fg/guzzle/composer.json>`
        :tab-width: 2   

      To execute the sample code, run the following command in the terminal
      in folder ``samples-doc/invoke-fg/guzzle``:

      .. code-block:: bash

        php src/invokeASync_AKSK.php

    .. tab:: Code using PHP "curl"

      .. literalinclude:: ../../../../samples-doc/invoke-fg/curl/src/invokeASync_AKSK.php
        :language: php
        :caption: :github_repo_master:`samples-doc/invoke-fg/curl/src/invokeASync_AKSK.php <samples-doc/invoke-fg/curl/src/invokeASync_AKSK.php>`
        :tab-width: 2

      .. literalinclude:: ../../../../samples-doc/invoke-fg/curl/composer.json
        :language: php
        :caption: :github_repo_master:`samples-doc/invoke-fg/curl/composer.json <samples-doc/invoke-fg/curl/composer.json>`
        :tab-width: 2   

      To execute the sample code, run the following command in the terminal
      in folder ``samples-doc/invoke-fg/curl``:

      .. code-block:: bash

        php src/invokeASync_AKSK.php

In both cases, you should see an output similar to the following in the terminal:

.. code-block:: bash
   :caption: Sample output of asynchronous invocation using AK/SK

   Response:  {"request_id": "3d1a4f5a-b4e2-4429-80c8-3d82d2fe8791"}
