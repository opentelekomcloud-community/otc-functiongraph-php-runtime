# dependency-multiple-sample

This sample shows how to use multiple libraries added as FunctionGraph dependencies together with dependencies bundled with the project.

Dependencies added to FunctionGraph function are unzipped in folder `$RUNTIME_CODE_ROOT\vendor` but **composer autoload** is not adapted correctly.

Steps needed:

1. Specify all needed dependencies in `composer.json`:

   Project dependencies are added in the **require** block:

   ```json
   "require": {
      "brick/date-time": "^0.9.0"
    },
   ``` 
      
   and dependencies added to FunctionGraph are added in the **require-dev** block:

   ```json
   "require-dev": {
      "opentelekomcloud-community/otc-api-sign-sdk-php": "^1.0",
      "opentelekomcloud-community/otc-functiongraph-php-runtime": "^1.0"
    },
   ``` 
  

2. Adapt composer.json to use another folder to install package dependencies
   in **config** block:

   ```json
   "config": {
    "vendor-dir": "dependencies"
   }
   ```

3. Exclude files not needed in the deployment, like:
  
   ```json
    "archive": {
      "exclude": [
        "Makefile",
        "terraform",
        "resources"
      ]
    },
   ```

4. Create zip file using

   ```bash
   make create_package
   ``` 


5. Use following command to include dependencies from project in your php file:
   ```php
     require_once getenv('RUNTIME_CODE_ROOT') . '/dependencies/autoload.php';
   ```

6. Add [FGDependenciesLoader.php](./src/FGDependenciesLoader.php) to your project and use following code snipped to include dependencies from FunctionGraph:
   ```php
   // include FunctionGraph dependencies
   include __DIR__.'/FGDependenciesLoader.php';
   $loader = new \FGDependenciesLoader\FGDependenciesLoader();   
   ```

As sample see: [index.php](./src/index.php)

