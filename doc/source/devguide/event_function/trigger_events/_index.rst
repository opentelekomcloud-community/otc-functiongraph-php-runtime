.. _devguide_event_function_trigger_events_index:

Function-supported trigger events
=================================

.. toctree::
   :maxdepth: 1
   :hidden:

   APIG Event <trigger_apig_event>
   CTS Event <trigger_cts_event>
   DDS Event <trigger_dds_event>
   DMS Kafka Event <trigger_dms4kafka_event>
   DMS RocketMQ Event <trigger_dms4rocketmq_event>
   Kafka (Open-Source) Event <trigger_kafkaopensource_event>
   LTS Event <trigger_lts_event>
   OBS Event <trigger_obs_event>
   SMN Event <trigger_smn_event>
   Timer Event <trigger_timer_event>

Overview of Trigger Events
---------------------------

Following table lists the cloud services that support triggering FunctionGraph functions.
These cloud services can be configured as event sources for FunctionGraph functions,
i.e., as triggers for the functions. 

After configuring the event source trigger, the FunctionGraph function will be automatically
invoked whenever the corresponding event is detected.

.. list-table:: Cloud service trigger events supported by FunctionGraph
    :header-rows: 1

    * - Cloud service/function
      - Triggering event

    * - Scheduled event function (TIMER)
      - Using TIMER's scheduled event feature, you can periodically call function code by
        specifying a fixed frequency (minutes, hours, days) or by specifying a Cron expression
        to call the function periodically.

        For instructions on using a TIMER timer trigger, please refer to the documentation on
        using :ref:`TIMER triggers <devguide_event_function_trigger_events_timer>`.
  
    * - API Gateway Service (APIG)
      - This can be achieved by calling FunctionGraph functions via HTTPS or HTTP using
        API Gateway's custom REST APIs and endpoints. 
        Various API operations (such as GET and PUT) can be mapped to specific FunctionGraph
        functions. When an HTTPS request is sent to that API endpoint,
        APIG will invoke the corresponding FunctionGraph function.

        For instructions on using APIG triggers, please refer to the documentation on 
        using :ref:`APIG triggers <devguide_event_function_trigger_events_apig>`.

    * - Cloud Trace Service (CTS)
      - Write a FunctionGraph function that, based on the CTS cloud audit service type and
        the event notifications required by the operation subscription, calls the FunctionGraph
        function by passing the collected operation records as parameters through a CTS trigger
        after the CTS cloud audit service obtains the subscribed operation records.
        The function then analyzes and processes key information in the logs, automatically
        repairing system, network, and other business modules, or generating alerts 
        via SMS, email, etc., to notify business personnel to take action.

        For instructions on using CTS triggers, please refer to the documentation on
        using :ref:`CTS triggers <devguide_event_function_trigger_events_cts>`.

    * - Document Database Service (DDS)
      - Using DDS triggers, the Functiongraph function can be fired to perform additional work
        each time a table in the database is updated.

        For instructions on using DDS triggers, please refer to the documentation
        on using :ref:`DDS triggers <devguide_event_function_trigger_events_dds>`.

    * - Distributed messaging service Kafka version (DMS for Kafka)
      - When a message is produced to a Topic on a Kafka instance, FunctionGraph consumes
        the message and triggers a function to perform additional work.

        For instructions on triggering and using the distributed messaging Kafka, please refer to:

        - Use :ref:`Kafka triggers <devguide_event_function_trigger_events_dms4kafka>`.
        - Using :ref:`open-source Kafka triggers <devguide_event_function_trigger_events_kafkaopensource>`       
        
    * - Log Tank Service (LTS)
      - Write FunctionGraph functions to process logs subscribed to from the Cloud Log Service.
        Once the Cloud Log Service collects the subscribed logs, it can call the FunctionGraph
        function by passing the collected logs as parameters.
        The FunctionGraph function code can be customized for processing, analysis, or loaded into other systems.

        For instructions on using LTS triggers, please refer to the documentation on
        using :ref:`LTS triggers <devguide_event_function_trigger_events_lts>`.

    * - Simple Message Notification Service (SMN)
      - Write FunctionGraph functions to handle SMN notifications. 
        When publishing a message to an SMN topic, the service can call the FunctionGraph function
        by passing the message payload as a parameter.
        The FunctionGraph function code can handle events, such as publishing the message
        to other SMN topics or sending the message to other cloud services.

        For instructions on using SMN message triggers, please refer to the documentation on
        using :ref:`SMN triggers <devguide_event_function_trigger_events_smn>`.

    * - Object Storage Service (OBS)
      - You can write FunctionGraph functions to handle OBS bucket events, such as object
        creation or deletion events.        

        For instructions on using OBS triggers, please refer to the documentation on
        using :ref:`OBS triggers <devguide_event_function_trigger_events_obs>`.

        

PHP event classes for trigger events
------------------------------------------------------

PHP classes for trigger events are located in the ``fg-events`` directory of the repository.

For example, to use the Timer Event trigger, you can use following composer.json:

.. code-block:: json

    {
      "name": "opentelekomcloud-community/fg-scratch-event-timer",
      "type": "project",
      
      "require": {
        "opentelekomcloud-community/otc-functiongraph-php-runtime": "*"
      },
      "archive": {
        "exclude": [
          "Makefile",
          "resources/**",
          "vendor/opentelekomcloud-community",
          "!vendor/opentelekomcloud-community/fg_events/fg-events/fg-timer-event/src"
        ]
      },
      "config": {
        "archive-format": "zip"
      }
    }


Then, you can import the Timer Event class in your code:

.. code-block:: php

   <?php
   require_once __DIR__ . '/../vendor/autoload.php';

   use fg_events\fg_timer_event\TimerEvent;

  
  