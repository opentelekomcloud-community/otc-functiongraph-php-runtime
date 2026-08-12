##########################################################
# Create Test Event
##########################################################
resource "opentelekomcloud_fgs_event_v2" "test_event" {
  function_urn = opentelekomcloud_fgs_function_v2.MyFunction.urn
  name         = "TestEvent"
  content = filebase64("../resources/test_event.json")
}
