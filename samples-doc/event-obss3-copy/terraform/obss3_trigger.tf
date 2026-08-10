##########################################################
# Create OBS Trigger listening for "ObjectCreate" in
# input bucket
##########################################################
resource "opentelekomcloud_fgs_trigger_v2" "obstrigger" {
  function_urn = opentelekomcloud_fgs_function_v2.MyFunction.urn
  type         = "OBS"
  event_data = jsonencode({
    "bucket" : opentelekomcloud_s3_bucket.inbucket.bucket
    "events" : [
      "s3:ObjectCreated:*"
    ]
    "name" : lower(format("%s-%s-%s", var.prefix, var.function_name, "event"))

  })
}