FunctionGraph Performance Optimization Practices
===============================================================

.. toctree::
   :hidden:
   
With the increasing popularity of serverless technology, performance optimization has become
crucial for improving application efficiency and user experience.

This article aims to explore the latest practices in FunctionGraph performance optimization,
providing a comprehensive analysis of how to achieve optimal performance in different scenarios,
from cold start optimization and function execution optimization, to offer practical guidance
and help you build more efficient and stable applications on FunctionGraph.

Code optimization
---------------------------------------------------------------
* Write idempotent code. Writing idempotent code for functions ensures that the function handles
  duplicate events in the same way.

* Use connection pools appropriately. Maintain connection reuse to reduce the cold start overhead
  of creating new connections (e.g., HTTP connection pools, database connection pools, Redis
  connection pools, etc.).

* Avoid reinitializing variable objects on every call (use global static variables, singletons, etc.).
  When calling middleware (such as Redis, Kafka, etc.), avoid repeatedly initializing the client in
  the handler method. Instead, initialize the client through the init method or global variables
  to reduce the overhead of client cold starts.

* Strengthen the client-side retry mechanism for exceptions. When a function call returns a status
  code other than 200 (such as 500, 429, 504, etc.), the client can add retry logic based on
  specific business needs, which can further ensure the reliability of the business.

* Use appropriate logging. When accessing third-party services, cloud services,
  and performing related operations in FunctionGraph functions, logs should be recorded to
  facilitate subsequent anomaly localization, performance optimization, and business analysis.

Performance stress testing
---------------------------------------------------------------

Performance testing of functions is a crucial step in ensuring the selection of optimal configurations.
During function load testing, platform-provided metrics, logs, call chains, and other tools can be
used to further analyze function performance data, thereby optimizing function configuration selection.
For details on specific observable metrics, please refer to the
:docs_otc:`Function Monitoring Overview <function-graph/umn/viewing_metrics_and_configuring_alarms/index.html>`.

Streamlined code and image slimming
---------------------------------------------------------------

Because FunctionGraph downloads function code during a cold start, this download process impacts startup
time. If the code file is too large, the download time will be extended, leading to a longer startup
time for FunctionGraph.

If a custom mirroring function is used, the larger the mirror, the longer the startup time will be.
Therefore, to reduce cold start time, the application should be optimized, such as removing unnecessary
code and reducing dependencies on third-party libraries.

In addition, some third-party libraries may contain test case source code, useless binary files,
and data files.
Cleaning up these files can reduce the download and decompression time of function code.

Use more memory
---------------------------------------------------------------

Allocating more memory to functions can improve CPU performance, thereby speeding up function
startup and execution. You can evaluate the impact of different memory configurations on function
performance by monitoring function execution time, and then choose the optimal memory size.

For detailed monitoring information, please refer to the :docs_otc:`monitoring metrics description <function-graph/umn/viewing_metrics_and_configuring_alarms/metrics/viewing_functiongraph_metrics.html#metric-description>`.
For steps on configuring memory, please refer to the
:docs_otc:`function configuration information <function-graph/umn/configuring_functions/index.html>`.

Use common dependency packages to speed up
---------------------------------------------------------------
When writing function code, third-party dependency libraries are often included.
During a cold start, the system downloads the necessary dependency packages;
if these packages are too large, it will extend the startup time.

FunctionGraph provides two types of dependency packages: public and private.
When using public dependency packages, FunctionGraph pre-downloads them to the execution nodes
to reduce download time. Therefore, it is recommended to prioritize using the public dependency
packages provided by FunctionGraph and minimize the use of private dependencies.

Configure reserved instances
---------------------------------------------------------------

Once a reserved instance is created, it will automatically load the function's code, dependencies,
and the initialization entry function, and persist in the environment. Therefore, configuring a
reserved instance for a function can avoid latency issues caused by cold starts.
For configuration instructions on reserved instances for functions, please refer to
Reserved Instance Management.

Use function initialization entry point
---------------------------------------------------------------

For functions that are called frequently, placing the initialization logic at the initialization
entry point can significantly reduce the execution time each time.

Examples include initializing HTTP connections and database connections. 

