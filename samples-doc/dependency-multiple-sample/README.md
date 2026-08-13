# dependency-multiple-sample

This sample shows how to use multiple libraries added as FunctionGraph dependencies together with dependencies bundled with the project.

Dependencies added to FunctionGraph function are unzipped in folder `$RUNTIME_CODE_ROOT\vendor` but **composer autoload** is not adapted correctly.

Steps needed:

1. Specify all needed dependencies in `composer.json` **require** block.
   Here we will use **brick/date-time** as project dependency and 
   **opentelekomcloud-community/*** dependencies are added to FunctionGraph:
   ```json
   "require": {
      "brick/date-time": "^0.9.0",
      "opentelekomcloud-community/otc-api-sign-sdk-php": "^1.0",
      "opentelekomcloud-community/otc-functiongraph-php-runtime": "^1.0"
    },
   ``` 

1. Adapt composer.json to use another folder to install package dependencies
   in **config** block:
   ```json
   "config": {
    "vendor-dir": "dependencies"
   }
   ```
1. Exclude dependencies installed on FunctionGraph in **archive** block.
  
   ```json
    "archive": {
      "exclude": [
        "Makefile",
        "terraform",
        "resources",
        "!dependencies",
        "dependencies/opentelekomcloud-community"
      ]
    },
   ```
   In this example all `opentelekomcloud-community` dependencies are excluded.

1. Use following command to include dependencies from project in your php file:
   ```php
     require_once __DIR__ . '/../dependencies/autoload.php';
   ```

1. Use following code snipped to include dependencies from FunctionGraph:
   ```php   
   $path = __DIR__ . '/../vendor';
   $dir      = new RecursiveDirectoryIterator($path);
   $iterator = new RecursiveIteratorIterator($dir);
   foreach ($iterator as $file) {
      $fname = $file->getFilename();
      if (preg_match('%\.php$%', $fname)
        && strpos($file->getPathname(), '/composer/') === false
        && strpos($file->getPathname(), '/autoload.php') === false
      ) {
            require_once $file->getPathname();
      }
   }
   ```
