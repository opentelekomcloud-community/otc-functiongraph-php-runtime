<?php

function handler($event, $context)
{

  $packages = array();

  $all = get_loaded_extensions();
  foreach ($all as $i) {
    $ext = new ReflectionExtension($i);
    $ver = $ext->getVersion();
    echo "$i - $ver" . PHP_EOL;
    $packages[$i] = $ver;
  }

  $output = array(
    "statusCode" => 200,
    "headers" => array(
      "Content-Type" => "application/json",
    ),
    "isBase64Encoded" => false,
    "body" => json_encode($packages),
  );
  return $output;
}
?>