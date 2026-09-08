.. _creating_an_event_function_using_a_container_image_built:

Creating an Event Function using a container image built with PHP
======================================================================

.. toctree::
   :maxdepth: 1
   :hidden:

For general details about how to use a container image
to create and execute an event function,
see :otc_fg_umn:`Creating an Event Function Using a Container Image and executing the Function <getting_started/creating_an_event_function_using_a_container_image_and_executing_the_function.html>`.

This chapter introduces how to create an image using PHP
and perform local verification for event functions.

.. note::

  You need to implement an **HTTP server** in the image listening to port **8000** to receive requests.

  Following request path is required:

  * **POST /invoke** is the function **execution** entry where trigger events are processed.

  Following request path is optional:

  * **POST /init** is the function **initialization** entry where you can perform
    initialization operations such as loading dependencies and preparing runtime environment.
    This entry is optional, and you can choose to implement it based on your needs.
    If you do not implement this entry, FunctionGraph will directly execute the function
    without initialization.

For full example, see: :github_repo_master:`container-event-flightphp <samples-doc/container-event-flightphp/>` sample in the GitHub repository.