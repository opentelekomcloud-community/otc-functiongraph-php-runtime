
###########################################################
# Custom role to allow FunctionGraph to access LTS and OBS
###########################################################
resource "opentelekomcloud_identity_role_v3" "role" {
  display_name  = format("%s-%s-role", var.prefix, var.function_name)
  description   = "Role for FunctionGraph to access OBS"
  display_layer = "project"

  statement {
    effect = "Allow"
    action = [
      "obs:*:*",
    ]
    resource = [
      "OBS:*:*:object:*",
      format("OBS:*:*:bucket:%s", opentelekomcloud_s3_bucket.inbucket.bucket),
      format("OBS:*:*:bucket:%s", opentelekomcloud_s3_bucket.outbucket.bucket),
    ]
  }

}

##########################################################
# Agency for FunctionGraph
# Attention: Crating agency will take some time.
# Calls to function after creating agency will fail until
# agency is set up.
##########################################################
resource "opentelekomcloud_identity_agency_v3" "agency" {
  depends_on            = [opentelekomcloud_identity_role_v3.role]
  delegated_domain_name = "op_svc_cff"

  name        = format("%s-%s-agency", var.prefix, var.function_name)
  description = "Agency for FunctionGraph to access OBS"

  project_role {
    all_projects = true
    #project      = "eu-de"
    roles = [
      opentelekomcloud_identity_role_v3.role.display_name
    ]
  }

}