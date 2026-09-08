.. _initializer:

Initializer function
=====================

Overview
---------------------------------------------
An initializer is a logic entry for initializing functions.
For a function with an initializer, FunctionGraph invokes the initializer
to initialize the function and then invokes the handler to process function
requests.
For a function without an initializer, FunctionGraph only invokes the handler
to process function requests.

See: :ref:`index_initializer` for usage of the Initializer.

Applicable Scenario
---------------------------------------------
FunctionGraph executes a function in the following steps:

1.  Allocate container resources to the function.
2.  Download function code.
3.  Use the runtime to load the function code.
4.  Initialize the function.
5.  Process the function request and return the result.

Steps 1, 2, and 3 are performed during a systematic cold start, ensuring a
stable latency through proper resource scheduling and process optimization.

Step 4 is performed during an application-layer cold start in complex
scenarios, such as loading large models for deep learning, building database
connection pools, and loading function dependencies.

To reduce the latency caused by an application-layer cold start,
FunctionGraph provides the initializer to identify function initialization
logic for proper resource scheduling.

Benefits of the Initializer
---------------------------------------------
* Isolate function initialization and request processing to enable clearer
  program logic and better structured and higher-performance code.

* Ensure a smooth function upgrade to prevent performance loss during
  the application layer's cold start initialization.
  Enable new function instances to automatically execute initialization
  logic before processing requests.

* Identify the overhead of application layer initialization, and accurately
  determine the time for resource scaling and the quantity of required
  resources. This feature makes request latency more stable when the
  application load increases and more function instances are required.
  
* If there are continuous requests and the function is not updated, the
  system may still reclaim or update existing containers.
  Although no code starts on the platform side, there are cold starts on
  the service side. The initializer can be used to ensure that requests
  can be processed properly.

Features of the Initializer
---------------------------------------------

The initializer of each runtime has the following features:

* No custom parameters

  The initializer does not support custom parameters and only uses the
  variables in context for logic processing.

* No return values

  No values will be returned for initializer invocation.

* Initialization timeout

  You can set an initialization timeout (≤ 300s) different from the timeout
  for invoking the handler.

* Execution duration

  Function instances are processes that execute function logic in a
  container and automatically scale if the number of requests changes.
  When a new function instance is generated, the system invokes the
  initializer and then executes the handler logic if the invocation is
  successful.

* One-time execution

  After each function instance starts, the initializer can only be executed
  once. If an instance fails to execute the initializer, the instance is
  abandoned and another instance starts to execute the initializer.
  A maximum of three attempts are allowed.
  If the initializer is executed successfully, the instance will only process
  requests upon invocation and will no longer execute the initializer
  again within its lifecycle.

* Naming rule

  For all runtimes except Java, the initializer can be named in the format
  of **[File name].[Initializer name]**, which is similar with the format of a
  handler name.

* Billing

  The initializer execution duration will be billed at the same rate as
  the function execution duration.
