.. _devguide_event_function_trigger_events_apig:

APIG Event Source
=================

API Gateway (APIG) is an API hosting service that helps enterprises to build,
manage, and deploy APIs at any scale. With APIG, your function can be invoked
through HTTPS by using a custom REST API and a specified backend. You can map
each API operation (such as, GET and PUT) to a specific function. APIG invokes
the relevant function when an HTTPS request is sent to the API backend.

For more information about how to use HTTPS calls to trigger functions, see
:docs_otc:`Using an APIG (Dedicated) trigger <function-graph/umn/creating_triggers/using_an_apig_dedicated_trigger.html>`.

Example APIG Event
------------------

.. literalinclude:: /../../fg-events/fg-apig-event/resources/apig_base64_event.json
    :language: json
    :caption: :github_repo_master:`apig_base64_event.json <fg-events/fg-apig-event/resources/apig_base64_event.json>`



Parameter description
---------------------

.. list-table::
   :header-rows: 1
   :widths: 20 15 40

   * - Parameter
     - Type
     - Description
   * - body
     - String
     - Actual request body in string format (Base64 encoded).
   * - requestContext
     - Map
     - Request information, including the API gateway configuration, request ID, authentication information, and source.
   * - httpMethod
     - String
     - HTTP method
   * - queryStringParameters
     - Map
     - Query strings configured in APIG and their actual values
   * - pathParameters
     - Map
     - Path parameters configured in APIG and their actual values
   * - headers
     - Map
     - Complete headers
   * - path
     - String
     - Complete path
   * - isBase64Encoded
     - Boolean
     - Default value: true (see Notes below)

Notes
-----
.. _ref_apig_event_base64:

- When calling a function using APIG, **isBase64Encoded** is valued true by
  default, indicating that the request body transferred to FunctionGraph is
  encoded using Base64 and must be decoded for processing.

- The function must return characters strings by using the following structure.

  .. code-block:: json

     {
       "isBase64Encoded": "true|false",
       "statusCode": "httpStatusCode",
       "headers": {"headerName":"headerValue"},
       "body": "..."
     }

.. note::
   For asynchronous invocation, of FunctionGraph, select the APIG trigger and click its 
   name to go to the APIG console, and select Asynchronous for the Invocation Mode.
   For details, see

   - :otc_fg_umn:`Asynchronous Invocation <invoking_the_function/asynchronous_invocation.html>` in user manual and
   - `Configuring Backend Settings <https://docs.otc.t-systems.com/api-gateway/umn/api_management/creating_an_api.html#configuring-backend-settings>`_ in API gateway user manual

Example
-------

.. literalinclude:: /../../samples-doc/scratch-event-apig/src/index.php
    :language: php
    :caption: :github_repo_master:`index.php <samples-doc/scratch-event-apig/src/index.php>`
    :tab-width: 2


Full sample code is available in the :github_repo_master:`samples-doc/scratch-event-apig`.

