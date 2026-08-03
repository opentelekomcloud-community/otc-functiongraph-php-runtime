##########################################################
# Create event function using container image 
##########################################################
resource "opentelekomcloud_fgs_function_v2" "MyFunction" {

  name   = format("%s_%s", var.prefix, var.function_name)
  app    = "default"
  
  handler   = "bootstrap"

  runtime = "http"

  code_type = "zip"
  func_code     = filebase64(format("${path.module}/../%s", var.zip_file_name))
  code_filename = basename(var.zip_file_name)

  description      = var.description
  memory_size      = 128
  timeout          = 30
  max_instance_num = 1

  # if you need security access key, security secret key and security token
  # to access other OTC services, set enable_auth_in_header to true
  enable_auth_in_header = true

  log_group_id   = opentelekomcloud_lts_group_v2.MyLogGroup.id
  log_group_name = opentelekomcloud_lts_group_v2.MyLogGroup.group_name

  log_topic_id   = opentelekomcloud_lts_stream_v2.MyLogStream.id
  log_topic_name = opentelekomcloud_lts_stream_v2.MyLogStream.stream_name

  # set some environment variables
  user_data = jsonencode({
    "RUNTIME_LOG_LEVEL" : "DEBUG",
    "REGION" : split("_", var.OTC_SDK_PROJECTNAME)[0]
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