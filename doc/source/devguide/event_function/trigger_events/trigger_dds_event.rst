.. _devguide_event_function_trigger_events_dds:

DDS Event Source (offline soon)
===============================

For the use of DDS triggers, please refer to
:docs_otc:`Using a DDS Trigger <function-graph/umn/creating_triggers/using_a_dds_trigger.html>`.

DDS example event
-----------------

.. literalinclude:: /../../fg-events/fg-dds-event/resources/dds_event.json
    :language: json
    :caption: :github_repo_master:`dds_event.json <fg-events/fg-dds-event/resources/dds_event.json>`


Parameter description
---------------------


.. list-table::
   :header-rows: 1
   :widths: 20 15 40

   * - Parameter
     - Type
     - Description

   * - event_source
     - String
     - Event source

   * - event_name
     - String
     - Event name
     
   * - region
     - String
     - Region where the DDS instance is located

   * - event_version
     - String
     - Event version

   * - size_bytes
     - String
     - Message bytes

   * - token
     - JSON String
     - Base64-encoded data

   * - full_document
     - JSON String
     - Complete file information

   * - ns
     - JSON String
     - Column name

   * - event_source_id
     - String
     - Event source ID
   

Example
-------

.. literalinclude:: /../../samples-doc/scratch-event-dds/src/index.php
    :language: php
    :caption: :github_repo_master:`index.php <samples-doc/scratch-event-dds/src/index.php>`

Full sample code is available in the :github_repo_master:`samples-doc/scratch-event-dds`.

