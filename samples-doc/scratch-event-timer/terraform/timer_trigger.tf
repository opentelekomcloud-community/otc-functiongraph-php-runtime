resource "opentelekomcloud_fgs_trigger_v2" "test" {
  function_urn = opentelekomcloud_fgs_function_v2.MyFunction.urn
  type         = "TIMER"
  status       = "DISABLED"
  event_data = jsonencode({
    "name" : replace(format("%s_%s_timer_trigger", var.prefix, var.function_name), "-", "_"),
    "schedule_type" : "Rate",
    "user_event" : "Created by terraform script",
    "schedule" : "30m"
  })
}
