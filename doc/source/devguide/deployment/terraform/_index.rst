.. _ref_terraform_deployment:

Deploying a function using Terraform
====================================

.. toctree::
   :maxdepth: 2
   :hidden:

   Setup Terraform <setuptf>
   Deploy from ZIP <deploy_from_zip/_index>
   Deploy from OBS <deploy_from_obs/_index>

To use Terraform as deployment tool, you should be familiar with
Terraform, see:
`What is Terraform <https://developer.hashicorp.com/terraform/intro>`_.


.. note::
  - This guide is not intended to replace Terraform documentation but gives
    hints on how to use Terraform with FunctionGraph.

  - As an alternative to Terraform, you can also use `OpenTofu <https://opentofu.org/docs/>`_.

To deploy event functions either using code from OBS or using code from ZIP,
see following:

- :ref:`Deploy FunctionGraph Event Function from ZIP<ref_deploy_from_zip>`
- :ref:`Deploy FunctionGraph Event Function from OBS<ref_deploy_from_obs>`

For more complex deployment scenarios, see the "terraform" folder
in the other sample files, e.g.:

- Deploy FunctionGraph as container in: :github_repo_master:`container-event-express <samples-doc/container-event-express>`
