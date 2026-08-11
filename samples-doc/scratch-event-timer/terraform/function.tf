##########################################################
# Create nodejs event function
##########################################################
resource "opentelekomcloud_fgs_function_v2" "MyFunction" {

  name   = format("%s_%s", var.prefix, var.function_name)
  app    = "default"
  
  handler =  var.handler_name

  initializer_handler = var.initializer_name
  initializer_timeout = 30

  runtime   = "PHP8.3"

  code_type = "zip"
  func_code     = filebase64(format("${path.module}/../%s", var.zip_file_name))
  code_filename = basename(var.zip_file_name)
  
  description      = var.description
  memory_size      = 512
  timeout          = 30
  max_instance_num = 1

  log_group_id   = opentelekomcloud_lts_group_v2.MyLogGroup.id
  log_group_name = opentelekomcloud_lts_group_v2.MyLogGroup.group_name

  log_topic_id   = opentelekomcloud_lts_stream_v2.MyLogStream.id
  log_topic_name = opentelekomcloud_lts_stream_v2.MyLogStream.stream_name

  # set some environment variables
  user_data = jsonencode({
    "RUNTIME_LOG_LEVEL" : "DEBUG",
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
