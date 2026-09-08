.. _devguide_event_function_trigger_events_timer:

Timer Event Source
==================

You can schedule a timer to invoke your code based on a fixed rate of minutes,
hours, or days or a cron expression. 

For the use of Timer triggers, please refer to
:docs_otc:`Using a Timer Trigger <function-graph/umn/creating_triggers/using_a_timer_trigger.html>`.

Timer example event
-------------------

.. literalinclude:: /../../fg-events/fg-timer-event/resources/timer_event.json
    :language: json
    :caption: :github_repo_master:`timer_event.json <fg-events/fg-timer-event/resources/timer_event.json>`

Parameter description
---------------------

.. list-table::
   :header-rows: 1
   :widths: 20 15 35

   * - Parameter
     - Type
     - Description
   * - version
     - String
     - Event version
   * - time
     - String
     - Time when an event occurs.
   * - trigger_type
     - String
     - Trigger type: **TIMER**
   * - trigger_name
     - String
     - Trigger name
   * - user_event
     - String
     - Additional information of the trigger

Example
-------

.. literalinclude:: /../../samples-doc/scratch-event-timer/src/index.php
    :language: php
    :caption: :github_repo_master:`index.php <samples-doc/scratch-event-timer/src/index.php>`

Full sample code is available in the :github_repo_master:`samples-doc/scratch-event-timer`.


