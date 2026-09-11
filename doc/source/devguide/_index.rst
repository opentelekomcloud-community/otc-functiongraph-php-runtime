.. _building_with_php:

Building with PHP
========================
.. toctree::
   :hidden:
   :maxdepth: 1

   Setup Development Environment <dev_environment/_index>
   Event Function<event_function/_index>
   HTTP Function<http_function/_index>
   Invoke FunctionGraph <invoke/_index>
   Bundled Extensions <bundled_libraries/_index>

FunctionGraph Types
-------------------

FunctionGraph provides 2 types of functions:

* **Event Functions**

  Event functions can be configured with event triggers and integrate
  a variety of products
  (such as object storage service OBS, distributed messaging service
  DMS, cloud log service LTS, etc.).

  See :doc:`Event Functions <event_function/_index>`

* **HTTP Functions**

  HTTP functions support mainstream Web application frameworks and can
  be accessed through a browser or called directly by a URL.

  See :doc:`HTTP Functions <http_function/_index>`

Both types of functions can be built either from **scratch** or by
using **container images**.


Building from scratch
----------------------


Supported PHP Runtimes for building from scratch
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

FunctionGraph currently supports the following PHP runtimes
for building functions from scratch:

.. _SupportedPHPRuntimes:

.. list-table:: Supported PHP runtimes
   :header-rows: 1
  
   * - Runtime
     - Identifier
     - PHP compilation environment (http functions)

   * - PHP 7.3
     - PHP7.3
     - /opt/function/runtime/php7.3/rtsp/php/bin/php

   * - PHP 8.3
     - PHP8.3
     - /opt/function/runtime/php8.3/rtsp/php/bin/php
   

For supported runtimes see also: :otc_fg_umn:`Runtimes <service_overview/product_features.html>` in User Guide.

.. note:: 

   If you need newer PHP runtimes, use custom container images
   to build your functions.
   
   For more information, see 

   - :ref:`devguide_event_function_container_index`
   - :ref:`devguide_http_function_container_index`


Building using container images
--------------------------------


Supported PHP Runtimes for building using container images
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

For building functions using container images, you can use any
PHP version that meets the requirements of your custom container image.
