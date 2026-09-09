Security best practices
===============================

.. toctree::
   :hidden:
   
Security is a shared responsibility between the cloud provider and you.
The cloud provider is responsible for the security of its cloud services
and provides a secure cloud environment.

You need to make reasonable use of the security features provided by the
cloud services to protect your data and ensure the safe use of cloud
services.


This document provides best practices for security when using FunctionGraph,
aiming to offer actionable guidelines to improve overall security
capabilities. By following this guidance, you can continuously assess the
security status of functions, effectively leverage the various security
features provided by FunctionGraph, enhance its overall security defenses,
ensure that data stored in FunctionGraph is not leaked or tampered with, and
safeguard the security of data transmission.

This article provides recommendations from the following dimensions, which
you can use to assess the use of FunctionGraph and configure security
accordingly based on your business needs.


Use trusted code and dependencies to avoid code vulnerabilities
---------------------------------------------------------------

* Before deploying function code, it is recommended to use  code inspection
  to perform static scanning and vulnerability analysis on the code to ensure
  code security.
* Use dependency libraries from reliable sources and update them regularly,
  and avoid using third-party libraries with known vulnerabilities.

Protect sensitive information and prevent its leakage
---------------------------------------------------------------

* If your user code or configuration contains sensitive information such as
  AK/SK, token, password, etc., it is strongly recommended to use :docs_otc:`encrypted
  environment variables <function-graph/umn/configuring_functions/configuring_environment_variables.html>`. Otherwise, this information may be displayed in plaintext
  in the user interface or API return results, which may lead to the leakage
  of sensitive information.

* For data involving user privacy (logs, personal information, etc.), it is
  recommended to perform anonymization during function processing and avoid printing
  logs in plaintext to prevent leakage of sensitive information.

* FunctionGraph can provide users with temporary code and download links, with a set
  expiration date. Users should prevent the temporary download links from being leaked
  to reduce the risk of code or library leaks.

Fine-grained access control and enabling identity authentication
-----------------------------------------------------------------

* :docs_otc:`When configuring agency permissions <function-graph/umn/configuring_functions/configuring_agency_permissions.html>` and AK/SK for FunctionGraph functions using 
  Unified Identity Authentication Service (IAM), the principle of
  least privilege should be followed to ensure that functions can only access specified
  resources.
  For example, restricting a function's read and write permissions to a specific OBS bucket
  can prevent unauthorized access.

* When configuring APIG triggers, it is recommended to enable IAM authentication or custom
  authentication to ensure that only authorized requests can trigger function execution.
  Additionally, APIG can be used to implement flow control to prevent malicious requests
  from exhausting resources.

Configure a VPC for the function to prevent external attacks
-----------------------------------------------------------------------

When a user function needs to access resources within a Virtual Private
Cloud (VPC), such as RDS, it is recommended to
:docs_otc:`configure a VPC <function-graph/umn/configuring_functions/configuring_networks.html>`
for the function to ensure that communication between the function and other cloud services takes
place in an isolated network environment.

Use function version control for rapid updates and rollbacks
------------------------------------------------------------------------

FunctionGraph supports function :docs_otc:`version management <function-graph/umn/configuring_functions/configuring_versions.html>`. It is recommended to create several
versions for each function and use the stable version in the production environment.
Additionally, the :docs_otc:`alias feature <function-graph/umn/configuring_functions/configuring_function_aliases.html>` allows you to associate specific versions of functions to
enable version switching, ensuring a rapid rollback in the event of a security issue.
