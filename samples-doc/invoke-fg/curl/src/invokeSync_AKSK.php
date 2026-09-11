<?php

require __DIR__ . '/../vendor/autoload.php';

use OTC\Request as OTCRequest;
use OTC\Signer;

function main(): int
{
  $region = getenv('OTC_SDK_REGION') ?: 'eu-de';
  $projectId = getenv('OTC_SDK_PROJECTID');
  $ak = getenv('OTC_SDK_AK');
  $sk = getenv('OTC_SDK_SK');

  $missing = [];
  foreach ([
    'OTC_SDK_PROJECTID' => $projectId,
    'OTC_SDK_AK' => $ak,
    'OTC_SDK_SK' => $sk,
  ] as $name => $value) {
    if (empty($value)) {
      $missing[] = $name;
    }
  }

  if ($missing) {
    fwrite(STDERR, 'Missing required environment variables: ' . implode(', ', $missing) . PHP_EOL);
    return 1;
  }

  $fgEndpoint = "https://functiongraph.{$region}.otc.t-systems.com";
  $functionName = 'php-sample-invoke-function';
  $functionVersion = 'latest';
  $functionApp = 'default';

  $functionUrn = sprintf(
    'urn:fss:%s:%s:function:%s:%s:%s',
    $region,
    $projectId,
    $functionApp,
    $functionName,
    $functionVersion
  );

  $invokeUri = $fgEndpoint . '/v2/' . $projectId . '/fgs/functions/' . $functionUrn . '/invocations';
  echo "Invoke URI: {$invokeUri}" . PHP_EOL;

  $payload = json_encode([
    'key' => 'Hello T-Cloud Public World - SYNC',
  ], JSON_THROW_ON_ERROR);

  $headers = [
    'Content-Type' => 'application/json;charset=utf8',
    'Host' => "functiongraph.{$region}.otc.t-systems.com",
    'X-Cff-Log-Type' => 'tail',
    'X-CFF-Request-Version' => 'v1',
    'X-Project-Id' => $projectId,
  ];

  $request = new OTCRequest('POST', $invokeUri, $headers, $payload);

  $signer = new Signer();
  $signer->Key = $ak;
  $signer->Secret = $sk;
  $signer->Sign($request);

  $curlHeaders = $signer->curlHeaders($request);

  try {
    $curlHandle = curl_init($invokeUri);
    if ($curlHandle === false) {
      throw new RuntimeException('Unable to initialize cURL');
    }

    curl_setopt_array($curlHandle, [
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_HTTPHEADER => $curlHeaders,
      CURLOPT_POSTFIELDS => $payload,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $responseBody = curl_exec($curlHandle);
    if ($responseBody === false) {
      throw new RuntimeException(curl_error($curlHandle));
    }

    $status = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
    curl_close($curlHandle);

    $responseBody = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);

    echo 'Status: ' . $status . PHP_EOL;
    echo 'Result: ' . ($responseBody['result'] ?? '') . PHP_EOL;
    echo 'Log: ' . ($responseBody['log'] ?? '') . PHP_EOL;
    return 0;
  } catch (Throwable $exception) {
    if (isset($curlHandle) && is_resource($curlHandle)) {
      curl_close($curlHandle);
    }

    fwrite(STDERR, 'Error: ' . $exception->getMessage() . PHP_EOL);
    return 1;
  }
}

exit(main());