Cold start Optimization Practices
===============================================================

.. toctree::
   :hidden:

Serverless architecture, with its pay-as-you-go pricing, automatic elastic scaling, and abstraction
of complexity, is gradually becoming the next-generation paradigm for cloud computing.
However, while serverless architecture brings great convenience, cold start becomes a real challenge
in applications with high real-time requirements. When building web services using serverless, if
the combined cold start and web service initialization time exceeds 5 seconds, it will undoubtedly
significantly degrade the user experience of your website.
Therefore, reducing cold start time and improving user experience is a problem that urgently needs to
be solved when building a serverless architecture.

The lifecycle of a serverless instance can be divided into three stages:

* **Initialization**: During this phase, FunctionGraph will attempt to unfreeze the previous execution
  environment. If there is no environment to unfreeze, FunctionGraph will create resources, download
  function code, initialize extensions and runtime, and then start running the initialization code
  (code outside the main program).

* **Execution**: In this phase, the instance begins executing the function after receiving the event.
  Once the function has finished running, the instance waits for the next event to be invoked.

* **Shutdown**: This phase is triggered if a FunctionGraph function does not receive any calls
  for a period of time. During the shutdown phase, the Runtime shuts down, then sends a shutdown
  event to each extension, and finally deletes the environment.

When a FunctionGraph event is triggered, if no function instance is currently active and available
for invocation, the function's code is downloaded and an execution environment is created.
The period from event triggering to the completion of the creation of the new FunctionGraph
environment is commonly referred to as the "cold start time." In a serverless architecture, the cold
start problem is unavoidable.

FunctionGraph has already made significant optimizations to the system-side cold start.
For the user-side, please refer to the following solutions.

Choose appropriate memory
---------------------------------------------------------------

Given a fixed number of concurrent requests, the larger the function memory, the more CPU resources
are allocated, and generally, the better the performance during a cold start.

Reduce code size and slim down images
---------------------------------------------------------------

Since FunctionGraph downloads function code during a cold start, this download process also affects
startup time. Larger codebases will take longer to download, increasing FunctionGraph's startup time;
similarly, larger custom mirror functions will result in longer startup times.
Therefore, to reduce cold start time, the application can be streamlined, such as by removing
unnecessary code and reducing unnecessary third-party library dependencies.

Additionally, some third-party libraries may contain test case source code, useless binary files,
and data files; deleting these can reduce function code download and decompression time.

Public dependency package acceleration
---------------------------------------------------------------

When developing applications, third-party dependency libraries are often used.
During a cold start, necessary dependency packages are downloaded, and large dependency
packages can significantly increase startup time. FunctionGraph offers both public and private
dependency packages.
For public dependency packages, FunctionGraph pre-downloads them to the execution nodes,
reducing download time.
Therefore, it is recommended to prioritize using FunctionGraph's public dependency packages and
minimize the use of private dependencies.

Preheating
---------------------------------------------------------------

When an event triggers a function, if an active function instance is available to be called at that
time, a cold start can be avoided, reducing response time. Warm-up can be achieved using the following
two methods:

* Use the timer trigger preheating function.
  For detailed usage instructions, please refer to :docs_otc:`Using Timer Triggers <function-graph/umn/configuring_triggers/using_a_timer_trigger.html#functiongraph-01-0207>`.

* Use reserved instances to avoid cold starts. For detailed usage instructions, please refer to
  the :docs_otc:`Reserved Instance Management <function-graph/umn/configuring_reserved_instances.html>`
  section.