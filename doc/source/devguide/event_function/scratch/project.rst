Setting up the PHP project for event functions
==========================================================

The following examples assumes that you have PHP 8.3 installed
you are using composer as the package manager and linux.


Creating a PHP project
---------------------------------

Project structure
^^^^^^^^^^^^^^^^^^^^^^^^

A minimal PHP FunctionGraph project is typically structured as follows:

.. code-block:: console
  :caption: Project structure

  /project-root
   ├─ dependencies      # PHP third-party dependencies (optional), created by composer (vendor directory)
   ├─ src
   |  └─ index.php
   ├─ composer.json
   └─ Makefile

.. note::
  The **dependencies** directory is where composer will install third-party PHP packages
  for the project.
  Normally this directory is named **vendor** by default, but in this project,
  it is named **dependencies** as specified in the **composer.json** file.

  If using FunctionGraph dependencies, those will be unzipped
  in the **$RUNTIME_CODE_ROOT/vendor** directory.


Sample code
^^^^^^^^^^^^^^^^^^^^^^^^


.. literalinclude:: ../../../../../samples-doc/scratch-event-project/src/index.php
  :language: php
  :caption: src/index.php

composer.json
^^^^^^^^^^^^^^^^^^^^^^^^

The **composer.json** file is used to manage the dependencies of a PHP
project. The following is a sample **composer.json** file:


.. literalinclude:: ../../../../../samples-doc/scratch-event-project/composer.json
  :language: json
  :caption: composer.json


Makefile
^^^^^^^^^^^^^^^^^^^^^^^^

The **Makefile** is used to automate the build and deployment process of a
PHP project. The following is a sample **Makefile**:

.. literalinclude:: ../../../../../samples-doc/scratch-event-project/Makefile
  :language: make
  :caption: Makefile


Deploying to FunctionGraph
---------------------------------

Create Zip
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

To upload the function code to FunctionGraph, you need to create package
of the project.

The directory structure of the zip package should be as follows:

.. code-block:: console
  :caption: Zip package structure

  /code.zip
   ├─ dependencies           # PHP third-party dependencies (optional)
   |  └─ ...
   ├─ src
   |  └─ index.php           # .php handler file (mandatory)
   └─ composer.json          # PHP project management file (optional)

You can use the following make target to create the package, which will include the dependencies listed in
the **composer.json** file:

.. code-block:: console

  make create_package


Create FunctionGraph function in console
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

1. Log in to the FunctionGraph console.
2. Click **Create Function** and select **Create from scratch**.
3. In **Basic Information**:

   - "FunctionType": **Event Function**.
   - "Region": select the region where you want to create the function.
   - "Function Name**: enter a **php_sample** as name for the function.
   - "Enterprise Project**: select **default**.
   - "Runtime**: select the PHP runtime version **PHP 8.3**.
   - "Agency": select **Use no agency**
   
4. Click **Create Function**.
5. Upload the created **code.zip** file to the function by
   clicking **Upload** > **Local ZIP**.

   The uploaded code will be automatically deployed on the
   FunctionGraph console.
   If you have modified the code, click **Deploy** again.

6. Modify the function handler:

   1. Click **Configuration** > **Basic Settings**.
   2. In the **Handler** field, enter the handler **src/index.handler**.
   3. Click **Save**.

7. Modify the initializer (if needed):

   1. Click **Configuration** > **Lifecycle**.
   2. enable **Initialization**
   3. In the **Function Initializer** field, enter the
      initializer **src/index.initializer**.
   4. Click **Save**.

Testing the function
^^^^^^^^^^^^^^^^^^^^^^^^

1. On the Code tab, click **Test**.
   In the Configure Test Event dialog box, create from **Blank Template** and set as:

    .. code-block:: json

       {
          "key": "value"
       }

2. Click **Create** to save the test event.
3. Click **Test** to test the function.
4. the Execution Result window is displayed on the right.
   You can check whether the function is executed successfully.

    .. image:: ./scratch_event_function_test.png
      :alt: Test Event Function

Function Execution Result Description
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The execution result consists of the function output, summary, and log output.

.. list-table::  Function execution result description
    :widths: 20 50 50
    :header-rows: 1

    * - Parameter
      - Successful Execution
      - Failed Execution

    * - Function output
      - The defined function output information is returned.
      - A JSON file that contains **errorMessage** and **errorType** is returned.
        The format is as follows:

        .. code-block:: json

          {
            "errorMessage": "error message",
            "errorType": "error type"
          }

        **errorMessage**: Error message returned by the runtime.
        **errorType**: Error type.

    * - Summary
      - **Request ID**, **Memory Configured**, **Execution Duration**,
        **Memory Used**, and **Billed Duration** are displayed.
      - **Request ID**, **Memory Configured**, **Execution Duration**,
        **Memory Used**, and **Billed Duration** are displayed.

    * - Log output
      - Function logs are printed. A maximum of 4 KB logs can be displayed.
      - Error information is printed. A maximum of 4 KB logs can be displayed.
