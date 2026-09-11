# Samples on how to invoke FunctionGraph functions

## Prerequisites

### Environment variables

| Environment variable   | Value                    |
| --------------------   | ------------------------ |
| ``OTC_SDK_PROJECTID`` | Project ID
| ``OTC_SDK_REGION``     | Region, default: "eu-de"
| ``OTC_SDK_AK``         | Access Key (*)
| ``OTC_SDK_SK``         | Secret Key

(*) with permission to invoke FunctionGraph.

### Deployed FunctionGraph

Deploy following FunctionGraph function using console:

* **Project** : ``OTC_SDK_PROJECTID`` (see above)
* **Region**: ``OTC_SDK_REGION`` (see above)
* **Name**: ``php-sample-invoke-function``
* **Runtime**: ``PHP 8.3``
* **Version**: ``latest``
* **Application**: ``default``
* **Code:** see: [src-fg/index.py](./src-fg/index.php)

## Synchronous invocation

### Using PHP Guzzle

**code:** [src/invokeSync_AKSK.php](./src/invokeSync_AKSK.php)

```bash
php invokeSync_AKSK.php
```


## ASynchronous invocation

### Using PHP Guzzle

**code:** [src/invokeASync_AKSK.php](./src/invokeASync_AKSK.php)

```bash
php invokeASync_AKSK.php
```
