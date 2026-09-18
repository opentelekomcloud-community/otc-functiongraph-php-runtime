.. _devguide_http_function_scratch_index:

Building FunctionGraph HTTP Functions with PHP from scratch
==========================================================================

.. toctree::
   :hidden:

Following chapter describes in short how to build FunctionGraph HTTP functions
using PHP from scratch.

Introduction
------------

For general details about creating HTTP functions from scratch and
executing an HTTP function,
see :otc_docs:`Creating a Function from Scratch and Executing the Function <function-graph/umn/creating_a_function/creating_a_function_from_scratch/creating_an_http_function.html#functiongraph-01-1442>`
in the User Guide.

Function Development Overview
------------------------------

See also :ref:`General Constraints for HTTP Functions <general_constraints_http>`

Step 1: Create a function
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

In FunctionGraph console, create a function with the following parameters:

- **Create with**: Create from scratch
- **Function Type**: HTTP Function
- **Region**: select the region where you want to create the function 
- **Function Name**: http-function-scratch

leave the other parameters with default values, and click **Create Function** to create the function.

Step 2: Write code for the function
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

For HTTP functions, the code is expected to listen for HTTP requests and send responses.

You can use any PHP web framework or library to implement the function code.

The following is a sample code using the `PHP built-in HTTP Web Server <https://www.php.net/manual/en/features.commandline.webserver.php>`_.

Full code for this example can be found at: :github_repo_master:`samples-doc/scratch-http`.

.. note::

   This sample is for demonstration purposes only. The built-in Web Server should not be used on a public network.

In the code editor, replace the default code for file `index.php` with the following
code, and click **Deploy** to save the code.

.. literalinclude:: ../../../../../samples-doc/scratch-http/index.php
   :caption: :github_repo_master:`samples-doc/scratch-http/index.php`
   :language: php
    

Step 3: Configure the bootstrap file
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

A file named `bootstrap` is used to start the function runtime and execute the function code.

The following is the code to be added in the `bootstrap` file for PHP runtime.

It starts PHP built-in Webserver and executes the `index.php` file.


.. code-block:: bash
   :caption: bootstrap

   cd $RUNTIME_CODE_ROOT/src
   /opt/function/runtime/[PHP_RUNTIME]/rtsp/php/bin/php -S 0.0.0.0:8000 [FUNCTION_FILENAME]

where

* **[PHP_RUNTIME]** is the PHP runtime to use (see :ref:`Supported PHP Runtimes <SupportedPHPRuntimes>`).
* **$RUNTIME_CODE_ROOT** is the environment variable that points to the root directory of your function code
  (/opt/function/code).
* **[FUNCTION_FILENAME]** is the name of the file that contains your function code (e.g., `index.php`).

In the code editor, replace the default code for file `bootstrap` with the following
code, and click **Deploy** to save the code.

.. literalinclude:: ../../../../../samples-doc/scratch-http/bootstrap
   :caption: :github_repo_master:`samples-doc/scratch-http/bootstrap`
   :language: bash 

Step 4: Configure environment variables
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
Click on **Configuration** tab and then select **Environment Variables**
to configure the environment variables for the function
(see also :otc_fg_umn:`Configuring Environment Variables <configuring_functions/configuring_environment_variables.html#functiongraph-01-0154>` in the User Guide)

.. list-table:: Add following environment variables:
   :widths: 20, 80, 30

   * - Key
     - Value
     - Encrypted

   * - USER_DATA
     - mydata
     - disabled

   * - SECRET_USER_DATA
     - mysecret
     - enabled

.. note::

   Encrypted environment variables are only accessible through the runtime environment variable
   **RUNTIME_USERDATA**. The environment variable **RUNTIME_USERDATA** is a JSON string that contains all configured FunctionGraph
   environment variables and their values.

Step 5: Create test events
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Click on **Test** tab, and click **Configure Test Event** to create test events with the following parameters:

(For HTTP functions only "API Gateway (Dedicated)" event template is supported.)

Execution event: **test_post**
""""""""""""""""""""""""""""""""

For **POST /** request create a test event named **test_post**:

.. literalinclude:: ../../../../../samples-doc/scratch-http/resources/test_post.json
   :caption: :github_repo_master:`samples-doc/scratch-http/resources/test_post.json`

Execution event: **test_get**
""""""""""""""""""""""""""""""""
For **GET /** request create a test event named **test_get**:

.. literalinclude:: ../../../../../samples-doc/scratch-http/resources/test_get.json
   :caption: :github_repo_master:`samples-doc/scratch-http/resources/test_get.json`

Step 5: Test the function
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Click **Test** to execute the function with the test events created in the previous step,
and you should see the following output in the ``Execution Result`` section

Execution result: **test_post**
""""""""""""""""""""""""""""""""

.. code-block:: json

    {
      "body": "eyJtZXNzYWdlIjogIkhlbGxvLCBXb3JsZCEifQ==",
      "headers": {
          "Content-Type": [
              "text/html; charset=UTF-8"
          ],
          "Date": [
              "Wed, 09 Sep 2026 10:27:22 GMT"
          ],
          "Host": [
              "host"
          ],
          "X-Powered-By": [
              "PHP/8.3.6"
          ]
      },
      "statusCode": 200,
      "isBase64Encoded": true
    }

The body in the output is base64 encoded. After decoding, you should see the following content:

  .. code-block:: html

      {"message": "Hello, World!"} 

Execution result: **test_get**
""""""""""""""""""""""""""""""""

.. code-block:: json

    {
        "body": "eyJtZXNzYWdlIjoiSGVsbG8sIEpvaG4hIiwidXNlcl9kYXRhIjoibXlkYXRhIiwic2VjcmV0X3VzZXJfZGF0YSI6Im15c2VjcmV0In0=",
        "headers": {
            "Content-Type": [
                "application/json"
            ],
            "Date": [
                "Fri, 18 Sep 2026 07:37:43 GMT"
            ],
            "Host": [
                "host"
            ],
            "X-Powered-By": [
                "PHP/8.3.6"
            ]
        },
        "statusCode": 200,
        "isBase64Encoded": true
    }

The body in the output is base64 encoded. After decoding, you should see the following content:

.. code-block:: html

    {"message":"Hello, John!","user_data":"mydata","secret_user_data":"mysecret"} 
