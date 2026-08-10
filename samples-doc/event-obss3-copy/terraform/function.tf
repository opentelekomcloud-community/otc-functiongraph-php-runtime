##########################################################
# Create Function
##########################################################
resource "opentelekomcloud_fgs_function_v2" "MyFunction" {
  # depends_on       = [opentelekomcloud_obs_bucket_object.code_object]
  name    = format("%s_%s", var.prefix, var.function_name)
  app     = "default"
  agency  = opentelekomcloud_identity_agency_v3.agency.name
  handler = var.handler_name

  runtime          = "PHP8.3"

  code_type = "zip"
  func_code = filebase64(format("${path.module}/../%s", var.zip_file_name))
  code_filename = basename(var.zip_file_name)

  description      = var.description
  memory_size      = 512
  timeout          = 30
  max_instance_num = 1

  log_group_id   = opentelekomcloud_lts_group_v2.MyLogGroup.id
  log_group_name = opentelekomcloud_lts_group_v2.MyLogGroup.group_name

  log_topic_id   = opentelekomcloud_lts_stream_v2.MyLogStream.id
  log_topic_name = opentelekomcloud_lts_stream_v2.MyLogStream.stream_name

  # set environment variables
  user_data = jsonencode({
    "OUTPUT_BUCKET" : opentelekomcloud_s3_bucket.outbucket.bucket,
    "OBS_ENDPOINT" : "obs.otc.t-systems.com"
  })

  tags = {
    "app_group" = var.tag_app_group
  }

}

output "MY_FUNCTION_URN" {
  value = opentelekomcloud_fgs_function_v2.MyFunction.urn
}

output "MY_FUNCTION_VERSION" {
  value = opentelekomcloud_fgs_function_v2.MyFunction.version
}
