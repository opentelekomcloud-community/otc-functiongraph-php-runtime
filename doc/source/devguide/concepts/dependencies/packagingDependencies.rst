Dependencies for PHP functions
====================================

This section describes how to create dependencies for PHP functions in FunctionGraph.

Example
--------

Before creating a dependency, ensure that PHP matching the function runtime
has been installed in the environment.

The following uses **PHP 8.3** as an example to describe how to create a **fg_events** dependency package.

1. Create a directory for your function and navigate to it.
 
  .. code-block:: bash

        # Create a directory for your function
        mkdir dependency-fg-events
        cd dependency-fg-events

2. Create a **composer.json** file and add dependencies.

    .. literalinclude:: ../../../../../samples-doc/dependency-fg-events/composer.json
       :language: json
       :caption: :github_repo_master:`composer.json <samples-doc/dependency-fg-events/composer.json>`
       :tab-width: 2

3. create a **Makefile** with a `create_package` target to automate the packaging process.

    .. literalinclude:: ../../../../../samples-doc/dependency-fg-events/Makefile
       :language: make
       :caption: :github_repo_master:`Makefile <samples-doc/dependency-fg-events/Makefile>`
       :tab-width: 2


5. Create vendor.zip file using

    .. code-block:: bash

        make create_package 

6. Deploy the dependency package to FunctionGraph as described in :otc_fg_umn:`Configuring Dependency Packages <configuring_dependencies/configuring_dependency_packages.html>`

