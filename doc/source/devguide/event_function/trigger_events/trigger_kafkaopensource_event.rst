.. _devguide_event_function_trigger_events_kafkaopensource:

Kafka (Open-Source) Event Source
=======================================

For the use of Open-Source Kafka triggers, please refer to
:docs_otc:`Using an Open-Source Kafka Trigger <function-graph/umn/creating_triggers/using_an_open-source_kafka_trigger.html>`.

Kafka example event
-------------------

.. literalinclude:: /../../fg-events/fg-kafkaopensource-event/resources/kafkaopensource_event.json
    :language: json
    :caption: :github_repo_master:`kafkaopensource_event.json <fg-events/fg-kafkaopensource-event/resources/kafkaopensource_event.json>`


Parameter description
---------------------


.. list-table::
   :header-rows: 1
   :widths: 20 15 40

   * - Parameter
     - Type
     - Description
   * - event_version
     - String
     - Event version
   * - event_time
     - String
     - Time when an event occurs
   * - trigger_type
     - String
     - Event type: **KAFKA**
   * - region
     - String
     - Region where a Kafka instance resides
   * - instance_id
     - String
     - Kafka instance ID
   * - messages
     - String[]
     - Message content
   * - topic_id
     - String
     - Message ID

Example
-------

.. literalinclude:: /../../samples-doc/scratch-event-kafkaopensource/src/index.php
    :language: php
    :caption: :github_repo_master:`index.php <samples-doc/scratch-event-kafkaopensource/src/index.php>`

Full sample code is available in the :github_repo_master:`samples-doc/scratch-event-kafkaopensource`.

