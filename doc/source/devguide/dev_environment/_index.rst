Set up development environment
===============================

.. toctree::
   :hidden:

To build and run the PHP runtime for FunctionGraph, you need to set up your
development environment by installing the PHP programming language.

Operating system
---------------------------------

As FunctionGraph is built on Linux, it is recommended to use a Linux-based operating system for development,
like:

- Windows Subsystem for Linux (WSL)
  see `How to install Linux on Windows with WSL <https://learn.microsoft.com/en-us/windows/wsl/install>`_,
- Linux

Windows can also be used for development, but you might encounter some issues if dependencies
of the PHP runtime do not support Linux.

Install PHP
---------------------------------

To build and run the PHP runtime for FunctionGraph, you need to install PHP.
The PHP version depends on the version of the PHP runtime you are using when deploying your functions.

You can download PHP from the official website: `https://www.php.net/downloads.php`

Install an IDE
---------------------------------
You can use any text editor or IDE to write PHP code.

.. note::
   Examples in this documentation were created using:

   - WSL and
   - Visual Studio Code.


Using container images
---------------------------------

To build functions using container images, you need to have
Docker installed on your system.

See `Get Docker <https://docs.docker.com/get-docker/>`_ for instructions
on how to install Docker on your system.
