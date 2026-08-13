# dependency-api-sign-sdk

## Installation

### Create dependency zip file

Use following command to create dependency zip file:

```bash
make create_package
```

This wil generate a `vendor.zip` file

### Install dependency to FunctionGraph

Install dependency as described in [Creating a Dependency](https://docs.otc.t-systems.com/function-graph/umn/configuring_dependencies/configuring_dependency_packages.html#creating-a-dependency).

Upload `vendor.zip` file generated in previous step.

## Usage

Following sample shows how to integrate the dependency in FunctionGraph code:

```php
<?php

require_once __DIR__ . "/vendor/autoload.php";

use OTC\fg_timer_event\TimerEvent;

function handler($event, $context) {
    $logger = $context->getLogger();

    $timerEvent = new TimerEvent($event);
    $logger->info('Trigger name from event: ' . $timerEvent->getTriggerName());

    $output = array(
        "statusCode" => 200,
        "headers" => array(
            "Content-Type" => "application/json",
        ),
        "isBase64Encoded" => false,
        "body" => json_encode($event),
    );
    return $output;
}

```

## Remark
In PHP projects, third-party dependencies downloaded using Composer need to be loaded using:

```php
require_once __DIR__ . "/vendor/autoload.php"

```
By default, FunctionGraph stores the decompressed files in a directory at the same level as the project code directory.
