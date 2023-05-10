<?php

require_once "vendor/autoload.php";

$DeyeObj = new \Deye\Deye();
$DeyeObj->setCredentials("admin:admin");
$DeyeObj->setInverterIp("192.168.1.19");

$resultset = $DeyeObj->inverterStatus();

print_r($resultset); // <- returns the inverter status information data as an Array.