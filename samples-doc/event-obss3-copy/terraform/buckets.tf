
##########################################################
# Input bucket for source images
##########################################################
resource "opentelekomcloud_s3_bucket" "inbucket" {
  bucket        = lower(format("%s-%s-%s", var.prefix, var.function_name, "images-input"))
  acl           = "private"

  # Warning: force_destroy will delete bucket on 
  # terraform destroy even if it contains objects
  force_destroy = true

  tags = {
    "app_group" = var.tag_app_group
  }

}

##########################################################
# Output bucket for copied images
# For output a different bucket is used to avoid potential
# risk of recursive invocation of FunctionGraph
##########################################################
resource "opentelekomcloud_s3_bucket" "outbucket" {
  bucket        = lower(format("%s-%s-%s", var.prefix, var.function_name, "images-output"))
  acl           = "private"

  # Warning: force_destroy will delete bucket on 
  # terraform destroy even if it contains objects
  force_destroy = true

  tags = {
    "app_group" = var.tag_app_group
  }

}

output "INPUT_BUCKET" {
  value = opentelekomcloud_s3_bucket.inbucket.bucket
}
output "OUTPUT_BUCKET" {
  value = opentelekomcloud_s3_bucket.outbucket.bucket
}