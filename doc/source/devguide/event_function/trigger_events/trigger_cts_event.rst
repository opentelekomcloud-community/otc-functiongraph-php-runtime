.. _devguide_event_function_trigger_events_cts:

CTS Event Source
===============================

For the use of CTS triggers, please refer to
:docs_otc:`Using a CTS Trigger <function-graph/umn/creating_triggers/using_a_cts_trigger.html>`.

CTS example event
-----------------

.. literalinclude:: /../../fg-events/fg-cts-event/resources/cts_event.json
    :language: json
    :caption: :github_repo_master:`cts_event.json <fg-events/fg-cts-event/resources/cts_event.json>`


Parameter description
---------------------


.. list-table::
   :header-rows: 1
   :widths: 20 15 40

   * - Parameter
     - Type
     - Description
   * - time
     - Int
     - (Epoch timestamp in milliseconds)
   * - user
     - Map
     - Information about the user who initiated this request
   * - request
     - Map
     - Event request content
   * - response
     - Map
     - Incident response content
   * - code
     - Int
     - Event response code, such as 200, 400
   * - service_type
     - String
     - Abbreviation of the sender, such as vpc, ecs, etc.
   * - resource_type
     - String
     - The sender resource type, such as vm, vpn, etc.
   * - resource_name
     - String
     - Resource name, such as the name of a virtual machine in the ecs service
   * - trace_name
     - String
     - Event name, such as: startServer, shutDown, etc.
   * - trace_type
     - String
     - The event source type, such as ApiCall
   * - record_time
     - String
     - The time when the cts service receives this trace (Epoch timestamp in milliseconds)
   * - trace_id
     - String
     - Unique identifier for the event
   * - trace_status
     - String
     - Status of the event

For full description of all parameters see
:docs_otc:`CTS Event Reference <cloud-trace-service/umn/trace_references/trace_structure.html#id1>`.

Example
-------

.. literalinclude:: /../../samples-doc/scratch-event-cts/src/index.php
    :language: php
    :caption: :github_repo_master:`index.php <samples-doc/scratch-event-cts/src/index.php>`

Full sample code is available in the :github_repo_master:`samples-doc/scratch-event-cts`.

