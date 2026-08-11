##########################################################
# Create Function
##########################################################
resource "opentelekomcloud_fgs_function_v2" "MyFunction_aksk" {
  # depends_on       = [opentelekomcloud_obs_bucket_object.code_object]
  name    = format("%s_%s", var.prefix, "sample-sdk-ecs-aksk")
  app     = "default"
  agency  = opentelekomcloud_identity_agency_v3.agency.name
  handler = "src/index_aksk.handler"

  runtime          = "PHP8.3"

  code_type = "zip"
  func_code = filebase64(format("${path.module}/../%s", var.zip_file_name))
  code_filename = basename(var.zip_file_name)

  description      = "start/stop ecs with aksk"
  memory_size      = 512
  timeout          = 30
  max_instance_num = 1

  # set environment variables
  user_data = jsonencode({
    "ECS_INSTANCE_ID" : var.ECS_INSTANCE_ID,
    "ECS_ENDPOINT" : "ecs.eu-de.otc.t-systems.com"
  })

  tags = {
    "app_group" = var.tag_app_group
  }

}

output "MY_FUNCTION_AKSK_URN" {
  value = opentelekomcloud_fgs_function_v2.MyFunction_aksk.urn
}

output "MY_FUNCTION_AKSK_VERSION" {
  value = opentelekomcloud_fgs_function_v2.MyFunction_aksk.version
}
