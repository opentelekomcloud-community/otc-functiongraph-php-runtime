.. _transferringKeys-ref:

Transferring Secret Keys through the request header
----------------------------------------------------

Credential information obtained through an **agency** assigned to 
a function created as 

- HTTP functions (created **from scratch** or using a **container image**),
- Event functions (created using a **container image** only)
  
can only be transferred through request headers.

For event functions created from scratch, the credential key information can be accessed
through the context object of the function in the function handler.

The credential key information includes Security Access Key (AK),
Security Secret Key (SK), and Security Token.

This allows you to call other T Cloud Public services using
API requests with the transferred keys for authentication.

To obtain the Security Access Key (AK), Security Secret Key (SK), 
and Security Token of an HTTP function as shown in Table 1,
perform the following steps:

1. Log in to the :fg_console:`FunctionGraph console <>` console and go
   to the details page of the HTTP function to be configured

2. Choose **Configuration** > **Permissions** and specify 
   an agency of `Agency Type` **Cloud Service** for 
   `Cloud Service` **FunctionGraph Service** with needed policies assigned.
   
3. Choose **Configuration** > **Advanced Settings** and enable
   **Include Keys**.

    .. figure:: console_advanced_settings.png
      :scale: 50 %
      :alt: Enable transferring secret keys

      Enable transferring secret keys through the request header.


.. list-table:: Table 1: Key information transferred by HTTP functions
   :header-rows: 1

   * - Header Name
     - Description

   * - X-CFF-Auth-Token
     - A token is an access credential issued to an IAM user
       to bear its identity and permissions.

   * - X-CFF-Security-Access-Key
     - A temporary Security Access Key (AK) is issued by the system to IAM users.

   * - X-CFF-Security-Secret-Key
     - A temporary Security Secret Key (SK) is issued by the system to IAM users.

   * - X-CFF-Security-Token
     - A temporary Security Token is issued by the system to IAM users.

.. note::

   - The temporary **Security-Access-Key**, **Security-Secret-Key** and **Security-Token**
     must be used together.

On details how to use the Security-Access-Key, Security-Secret-Key,
and Security-Token to call other T Cloud Public services using API request,
see :github_php_sign_sdk:`Developer guide for request signing for PHP<>`

.. hint::

  In Terraform set **enable_auth_in_header**  to **true** in the resource
  **opentelekomcloud_functiongraph_function_v2** to enable transferring
  keys through the request header.
