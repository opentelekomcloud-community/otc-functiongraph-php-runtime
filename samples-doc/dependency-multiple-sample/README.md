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

2. Exclude dependencies installed on FunctionGraph in **archive** block but those of your project with ```!dependencies```:
  
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

3. Use following command to include dependencies from project in your php file:
   ```php
     require_once getenv('RUNTIME_CODE_ROOT') . '/dependencies/autoload.php';
   ```

4. Add [FGDependenciesLoader.php](./src/FGDependenciesLoader.php) to your project and use following code snipped to include dependencies from FunctionGraph:
   ```php
   // include FunctionGraph dependencies
   include __DIR__.'/FGDependenciesLoader.php';
   $loader = new \FGDependenciesLoader\FGDependenciesLoader();   
   ```

As sample see: [index.php](./src/index.php)

