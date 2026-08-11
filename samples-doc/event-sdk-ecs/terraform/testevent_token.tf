resource "opentelekomcloud_fgs_event_v2" "test_start_token" {
  function_urn = opentelekomcloud_fgs_function_v2.MyFunction_token.urn
  name         = "start"
  content = filebase64("../resources/event_start.json")
}

resource "opentelekomcloud_fgs_event_v2" "test_stop_token" {
  function_urn = opentelekomcloud_fgs_function_v2.MyFunction_token.urn
  name         = "stop"
  content = filebase64("../resources/event_stop.json")
}
