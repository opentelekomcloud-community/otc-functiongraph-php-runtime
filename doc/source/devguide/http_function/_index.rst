HTTP Functions
==========================

.. toctree::
   :hidden:

   Create from scratch <scratch/_index>
   Container Image <container/_index>

HTTP functions support mainstream Web application frameworks
and can be accessed through a browser or called directly by a URL.

Types of Building HTTP Functions
------------------------------------

You can build FunctionGraph HTTP functions the following ways:

* :ref:`From Scratch  <devguide_http_function_scratch_index>`
* :ref:`Using Container Image  <devguide_http_function_container_index>`

.. note::
  The option "Select template" is not supported for PHP HTTP functions.

.. _general_constraints_http:

General Constraints for HTTP Functions
-----------------------------------------

Following are the general constraints of HTTP functions:

- HTTP functions can be used with APIG triggers.
  According to the forwarding protocol between FunctionGraph and APIG,
  a valid HTTP function response must contain:

  - **body(String)**,
  - **statusCode(int)**,
  - **headers(Map)**, and
  - **isBase64Encoded(boolean)**.

  By default, the response is encoded using Base64.
  The default value of **isBase64Encoded** is **true**.

  For details about the constraints, see :ref:`Base64 Decoding and
  Response Structure<ref_apig_event_base64>`.

- The bound IP address is **127.0.0.1.**

- By default, port **8000** is enabled for HTTP functions,
  no other port can be used.

- By default, an account can create a maximum of 400 functions.
  (This quota can be increased upon request.)

- HTTP functions cannot be executed for a long time,
  invoked asynchronously, or retried.

- When a function initiates an HTTP request, the request IP address
  is dynamic for private network access and fixed for public network access.

Common Request Headers of HTTP Functions
-----------------------------------------

HTTP request headers are an important part of the HTTP protocol for
passing metadata.
When a function is invoked, specific metadata or configuration information
can be passed. Following Table describes the common request headers carried
by functions by default.

.. list-table:: Common Request Headers of HTTP Functions
   :header-rows: 1

   * - Header Name
     - Description
   * - x-cff-request-id
     - ID of the current request.
   * - x-cff-memory
     - Memory allocated to the function.
   * - x-cff-timeout
     - Function timeout.
   * - x-cff-func-version
     - Function version.
   * - x-cff-func-name
     - Function name.
   * - x-cff-project-id
     - Project ID of the function.

       .. note:: **x-cff-project-id in the header currently returns always "sn".** 

          To get correct Project ID use instead:

          .. code-block:: php

              $region = getenv("RUNTIME_PROJECT_ID");


   * - x-cff-package
     - App to which the function belongs.
   * - x-cff-region
     - Region where the function is located.

       .. note:: **x-cff-region in the header currently returns always "cn".** 

          To get correct region define a environment variable REGION 
          with correct region value and use e.g.:
            
          .. code-block:: php

              $region = getenv("REGION") ?: "eu-de";
  

.. note::

  The security information of HTTP functions:

     - x-cff-auth-token
     - x-cff-security-access-key
     - x-cff-security-secret-key
     - x-cff-security-token

   can be transferred only through request headers.
   For details about how to obtain the AK, SK, and token of HTTP functions,
   see :ref:`Transferring Secret Keys Through the Request Header <transferringKeys-ref>`

