##########################################################
# Create Test Events
##########################################################
resource "opentelekomcloud_fgs_event_v2" "test_event_get_json" {
  function_urn = opentelekomcloud_fgs_function_v2.MyFunction.urn
  name         = "get_json"
  content = filebase64("../resources/apig_get_json.json")
}

resource "opentelekomcloud_fgs_event_v2" "test_event_get_search" {
  function_urn = opentelekomcloud_fgs_function_v2.MyFunction.urn
  name         = "get_search"
  content = filebase64("../resources/apig_get_search.json")
}
