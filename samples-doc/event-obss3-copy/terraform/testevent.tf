##########################################################
# Create Test Event
##########################################################
resource "opentelekomcloud_fgs_event_v2" "test_event" {
  function_urn = opentelekomcloud_fgs_function_v2.MyFunction.urn
  name         = "UploadTest"
  content = base64encode(jsonencode({
    "Records" = [{
      "eventVersion" = "2.0"
      "eventSource"  = "obs"
      "eventTime"    = "2025-10-24T08:30:00+08:00"
      "eventName"    = "ObjectCreated:PutObject"
      "awsRegion"    = "eu-de"
      "userIdentity" = {
        "principalId" = "EXAMPLE"
      }
      "requestParameters" = {
        "sourceIPAddress" = "EXAMPLE"
      }
      "s3" = {
        "configurationId" = "testConfigRule"
        "bucket" = {
          "name" = opentelekomcloud_s3_bucket.inbucket.id
          "ownerIdentity" = {
            "principalId" = "EXAMPLE"
          }
          "arn" = opentelekomcloud_s3_bucket.inbucket.arn
        }
        "object" = {
          "key" = "otc.jpg"
          "size" = 1024
          "eTag" = "0123456789abcdef0123456789abcdef"
          "sequencer" = "0A1B2C3D4E5F678901"
        }
      }
    }]
  }))
}