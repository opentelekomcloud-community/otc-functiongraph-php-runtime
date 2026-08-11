# Terraform variables

# prefix of all resources
prefix        = "php"

# description of the function
description = "Sample scratch-event-timer"

# name of the function (will be prefixed)
function_name = "scratch-event-timer"

# handler function name defined in your code, e.g. "index.handler"
handler_name = "src/index.handler"

# initializer function name defined in your code, e.g. "index.initializer"
initializer_name = "src/index.initializer"

# name of zip file to deploy
zip_file_name = "code.zip"

# resources will be tagged with this app_group tag
tag_app_group = "scratch-event-timer"
