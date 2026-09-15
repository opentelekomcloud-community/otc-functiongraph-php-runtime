.. _event-sdk-obs:

event-sdk-obs
-------------------

Example on how to use OBS in FunctionGraph.

Source code for this sample can be found on :github_repo_master:`GitHub <samples-doc/event-sdk-obs>`.

Deploy to T Cloud Public FunctionGraph
======================================

Create following FunctionGraph function using FunctionGraph console:


Create deployment package:
""""""""""""""""""""""""""""""""""""
In project root directory, run the following command to create a deployment package:

.. code-block:: bash

   make create_package

this will create a deployment package named **code.zip** in the project root directory.

Create function:
""""""""""""""""""

- Create with: **Create from scratch**
- Function Type: **Event Function**
- Region: **your region** (e.g. eu-de)
- FunctionName: **php_obs_sample**
- Enterprise Project: **default**
- Runtime: **PHP 8.3**
- Agency: **specify an agency with OBS permissions**  
  (e.g. Permission `OBS Administrator`)


In **Code** tab, upload the deployment package **code.zip**.


and click **Deploy**.

Configure function:
""""""""""""""""""""

- `Basic Settings`

   - Handler: **index.handler**

- `Environment variables`

   - **OBS_ENDPOINT_URL** = **https://obs.eu-de.otc.t-systems.com**
  


Test the Function
""""""""""""""""""""

- Create any Test events.

- Click **Test**

