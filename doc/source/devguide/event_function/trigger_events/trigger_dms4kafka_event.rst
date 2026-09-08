.. _devguide_event_function_trigger_events_dms4kafka:

DMS for Kafka Event Source
=======================================

DMS for Kafka is a message queuing service that provides Kafka premium
instances. If you create a Kafka trigger for a function, when a message is sent
to a Kafka instance topic, FunctionGraph will retrieve the message and trigger
the function to perform other operations.

For the use of Kafka triggers, please refer to
:docs_otc:`Using a Kafka Trigger <function-graph/umn/creating_triggers/using_a_kafka_trigger.html>`.

Kafka example event
-------------------

.. literalinclude:: /../../fg-events/fg-dms4kafka-event/resources/dms4kafka_event.json
    :language: json
    :caption: :github_repo_master:`dms4kafka_event.json <fg-events/fg-dms4kafka-event/resources/dms4kafka_event.json>`


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

.. literalinclude:: /../../samples-doc/scratch-event-dms4kafka/src/index.php
    :language: php
    :caption: :github_repo_master:`index.php <samples-doc/scratch-event-dms4kafka/src/index.php>`

Full sample code is available in the :github_repo_master:`samples-doc/scratch-event-dms4kafka`.

