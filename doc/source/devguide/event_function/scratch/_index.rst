.. _devguide_event_function_scratch_index:

Building FunctionGraph Event Functions with PHP from scratch
==========================================================================

Following chapter describes how to build FunctionGraph event functions
using PHP from scratch:

.. toctree::
   :hidden:

   Project <project.rst>
   Context <context.rst>
   Initializer <initializer.rst>

Introduction
------------

For general details about creating event functions from scratch and
executing an event function,
see :otc_docs:`Creating a Function from Scratch and Executing the Function <function-graph/umn/creating_a_function/creating_a_function_from_scratch/creating_an_event_function.html>`
in the user manual.

Function Development Overview
------------------------------

Use the following syntax when creating a handler function in PHP:

.. code-block:: php

  function handler($event, $context) {
    // Your code here
    return $data;
  }

* **handler**:
  Name of the function that FunctionGraph invokes to execute your code.

  The name must be consistent with that you define when creating a function
  in FunctionGraph.

* **event**:
  Event parameter defined for the function.

  The parameter is in **JSON** format.

* **context**:
  Runtime information provided for executing the function.

  See :ref:`context` for details.

Responses are output through **return**.

If function throws an exception, the function execution is
considered failed and the error message object is returned.



.. _index_handler:

Handler
^^^^^^^^^^^^^^^^^^^^

The FunctionGraph handler is the method in your function code that processes events.
When the function is invoked, FunctionGraph runs the handler method.
The function runs until the handler returns a response, exits or times out.

The handler method of a PHP function is in the format of
**[file name].[function name]**.

By default it is **index.handler**. 

You can configure the handler on the FunctionGraph console (Configuration -> Basic Settings -> Handler).

For example, if you set the handler to **index.handler** in your function configuration,
FunctionGraph will load the **handler** function defined in the **index.php**
file.

Defining and accessing the input event object
""""""""""""""""""""""""""""""""""""""""""""""""""""""""""

The input event object is defined in the **event** parameter of the handler function.
The event parameter is in **JSON** format. You can define the content of the event parameter as needed.

When working with the event parameter, you can directly access the content of the event parameter
by using the dot notation or bracket notation.

For example, if the event parameter is defined as follows:

.. code-block:: json

  {
    "order_id": "592f8c9e-1b6a-4c3e-9d5b-1234567890ab",
    "amount": "100.00",
    "item": "book"
  }   

You can access the value of **order_id** by using the following code:

.. code-block:: php

  $order_id = $event["order_id"];  // Using bracket notation
  // or
  $order_id = isset($event["order_id"]) ? $event["order_id"] : null;  // Using get method

You can define the expected shape of the input event using type hints.

For example:


.. code-block:: php


    function handler($event, $context) {
      $order_id = $event["order_id"];  // Using bracket notation
      // or
      $order_id = isset($event["order_id"]) ? $event["order_id"] : null;  // Using get method
      $amount = $event["amount"];
      $item = $event["item"];

      // Process the order event...

      return "Order processed successfully";

    }

After you define the event parameter, FunctionGraph code completion
will help you access the content of the event parameter.

As alternative, you can also use predefined javascript objects to define the expected shape of the input event.

For trigger events, the event parameter is predefined by FunctionGraph.

You can refer to the following documentation for details about the predefined event parameters for different trigger events.
See: :ref:`devguide_event_function_trigger_events_index` for details.


Return value
"""""""""""""""""""""""""""

The return value of the function output:

* **Successful execution**: The defined function output information is returned.

* **Failed execution**: Due to an thrown exception (errorType(message)),
  a error message JSON object containing
  **errorMessage**, **errorType** and **stackTrace** is returned.

  The format is as follows:

  .. code-block:: json

    {
      "errorMessage": "function invocation exception, error: [message]",
      "errorType": "[errorType]",
      "stackTrace": [
         "stack trace"
      ]
    }

  where **errorMessage** is the error message returned by the runtime and
  **errorType** is the error type.


.. _index_initializer:

Initializer
^^^^^^^^^^^^^^^^^^^^

For details about the initializer, see :ref:`initializer`.

The initializer is in the format of **[File name].[Initializer name]**.

For example, if the initializer is named **index.initializer**, FunctionGraph
loads the initializer function defined in the **index.php** file.

To use PHP to build initialization logic, define a PHP function as the
initializer.

The following is a simple initializer:

.. code-block:: php

  function initializer($context) {
    $logger = $context->getLogger();
    $logger->info("Initializing:", $context->getFunctionName());

    return;
  }
  

* **Function name**:
  The function name **initializer** must be the initializer function
  name specified for a function.

  For example, if the initializer is named **index.initializer**, FunctionGraph
  loads the initializer function defined in the **index.php** file.

* **context**:
  The **context** parameter contains the runtime information about a function.
  For example, request ID, temporary AK, and function metadata.
  See :ref:`context` for details.
