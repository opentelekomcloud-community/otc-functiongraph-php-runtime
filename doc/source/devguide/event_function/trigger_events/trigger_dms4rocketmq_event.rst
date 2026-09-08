.. _devguide_event_function_trigger_events_dms4rocketmq:

DMS for RocketMQ Event Source
=======================================

DMS for RocketMQ is a message queuing service that provides RocketMQ premium
instances. If you create a RocketMQ trigger for a function, when a message is sent
to a RocketMQ instance topic, FunctionGraph will retrieve the message and trigger
the function to perform other operations.

For the use of RocketMQ triggers, please refer to
:docs_otc:`Using a RocketMQ Trigger <function-graph/umn/creating_triggers/using_a_rocketmq_trigger.html>`.

RocketMQ example event
----------------------

.. literalinclude:: /../../fg-events/fg-dms4rocketmq-event/resources/rocketmq_event.json
    :language: json
    :caption: :github_repo_master:`rocketmq_event.json <fg-events/fg-dms4rocketmq-event/resources/rocketmq_event.json>`


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
     - Event type: **ROCKETMQ**
   * - region
     - String
     - Region where a RocketMQ instance resides
   * - instance_id
     - String
     - RocketMQ instance ID
   * - messages
     - String[]
     - Message content
   * - topic_id
     - String
     - Message ID

Example
-------

.. literalinclude:: /../../samples-doc/scratch-event-dms4rocketmq/src/index.php
    :language: php
    :caption: :github_repo_master:`index.php <samples-doc/scratch-event-dms4rocketmq/src/index.php>`

Full sample code is available in the :github_repo_master:`samples-doc/scratch-event-dms4rocketmq`.

