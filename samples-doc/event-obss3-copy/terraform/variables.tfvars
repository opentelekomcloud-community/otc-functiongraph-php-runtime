# prefix of all resources
prefix = "php"

# description of the function
description = "Sample event-obss3-copy"

# name of the function (will be prefixed)
function_name = "event-obss3-copy"

# handler function name defined in your code, e.g. "index.handler"
handler_name = "src/s3copy.handler"

# name of zip file to deploy
zip_file_name = "code.zip"

# resources will be tagged with this app_group tag
tag_app_group = "event-obss3-copy"
