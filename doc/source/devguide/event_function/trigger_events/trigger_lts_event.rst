.. _devguide_event_function_trigger_events_lts:

LTS  Event Source
==================

You can write FunctionGraph functions to process logs subscribed to Cloud Log
Service. When Cloud Log Service collects subscribed logs, you can call
FunctionGraph functions by passing the collected logs as parameters (LTS sample
events). FunctionGraph function code can be customized, analyzed, or loaded
into other systems. 

For the use of LTS log triggers, please refer to
:docs_otc:`Using a LTS Trigger <function-graph/umn/creating_triggers/using_an_lts_trigger.html>`.

Example LTS Event
-----------------

.. code-block:: json

  {
    "lts": {
        "data": "eyJsb2dzIjoiW3tcIm1lc3NhZ2VcIjpcIjIwMTgtMDgtMDgvMDg6MDg6MDggW1dSTl0gW3Rlc3QuZ286MDhdVGhpcyBpcyBhIHRlc3QgbWVzc2FnZS5cIixcInRpbWVcIjoxNTMwMDA5NjUzMDU5LFwiaG9zdF9uYW1lXCI6XCJlY3MtdGVzdFwiLFwiaXBcIjpcIjE5Mi4xNjguMS4xXCIsXCJwYXRoXCI6XCJ2YXIvbG9nL3Rlc3QubG9nXCIsXCJsb2dfdWlkXCI6XCI2NjNkNjkzMC03OTJkLTExZTgtOGIwOC0yODZlZDQ4OGNlNzBcIixcImxpbmVfbm9cIjoxfV0iLCJvd25lciI6IjYyODBlMTcwYmQ5MzRmNjBhNGQ4NTFjZjVjYTA1MTI5IiwibG9nX2dyb3VwX2lkIjoiOTdhOWQyODQtNDQ0OC0xMWU4LThmYTQtMjg2ZWQ0ODhjZTcwIiwibG9nX3RvcGljX2lkIjoiMWE5Njc1YTctNzg0ZC0xMWU4LTlmNzAtMjg2ZWQ0ODhjZTcwIn0="
    }
  }

The data value in LTS is base64 encoded.

After decoding, the content is as follows:

.. code-block:: json

   {"logs":"[{\"message\":\"2018-08-08/08:08:08 [WRN] [test.go:08]This is a test message.\",\"time\":1530009653059,\"host_name\":\"ecs-test\",\"ip\":\"192.168.1.1\",\"path\":\"var/log/test.log\",\"log_uid\":\"663d6930-792d-11e8-8b08-286ed488ce70\",\"line_no\":1}]","owner":"6280e170bd934f60a4d851cf5ca05129","log_group_id":"97a9d284-4448-11e8-8fa4-286ed488ce70","log_topic_id":"1a9675a7-784d-11e8-9f70-286ed488ce70"}


Parameter description
---------------------

.. list-table::
   :header-rows: 1
   :widths: 20 15 40

   * - Parameter
     - Type
     - Description
   * - data
     - String
     - Base64 encoded data

Example
-------

.. literalinclude:: /../../samples-doc/scratch-event-lts/src/index.php
    :language: php
    :caption: :github_repo_master:`index.php <samples-doc/scratch-event-lts/src/index.php>`

Full sample code is available in the :github_repo_master:`samples-doc/scratch-event-lts`.

