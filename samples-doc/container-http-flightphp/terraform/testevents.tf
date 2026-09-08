##########################################################
# Test events for FastAPI function to be used in
# OpenTelekomCloud FunctionGraph console
##########################################################

##########################################################
# Test event for /
##########################################################
resource "opentelekomcloud_fgs_event_v2" "event_search" {
  function_urn = opentelekomcloud_fgs_function_v2.MyFunction.urn
  name         = "Search"
  content = filebase64("../resources/apig_get_search.json")
}

resource "opentelekomcloud_fgs_event_v2" "event_json" {
  function_urn = opentelekomcloud_fgs_function_v2.MyFunction.urn
  name         = "Json"
  content = filebase64("../resources/apig_post_json.json")
}