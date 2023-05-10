# deye-inverter-status

*Simple library for reading Deye inverter status information.*

Reads the current [deye inverter](https://www.deyeinverter.com/) status and returns all 
information in an array. This script accesses the inverter and reads out the status.html page, 
which contains all necessary information from the inverter.

Works with **DEYE SUN600 / SUN800**.

## Features

* Lightweight - Portable with only one file
* Easy - Easy to use, minimal requirements.
* Free - Open source and licensed under MIT license

## Requirement

* PHP Version >= 8.2
* PHP Modules ext-curl, ext-dom

## Installation

**Install via composer**

Add *turbopixel/deye-inverter-status* to the composer.json file.

```bash
composer require turbopixel/deye-inverter-status
```

And update composer

```bash
composer update
```

**Alternative** clone this repository:

```bash
git clone git@github.com:turbopixel/deye-inverter-status.git
```

## Example

> It is important that the inverter is connected to the same network!

The library is designed to be very simple. Copy the following code, adjust 
the variables and execute the PHP file on the console.

**inverter.php**
```php 
<?php

require_once "vendor/autoload.php";

$DeyeObj = new \Deye\Deye();
$DeyeObj->setCredentials("admin:admin");
$DeyeObj->setInverterIp("192.168.1.19");

$resultset = $DeyeObj->inverterStatus();

print_r($resultset); // <- returns the inverter status information data as an Array.
```

Run on console:

```bash
php -f inverter.php
```

### Example output

```json
[
  {
    "var": "webdata_sn",
    "content": ""
  },
  {
    "var": "webdata_msvn",
    "content": ""
  },
  {
    "var": "webdata_ssvn",
    "content": ""
  },
  {
    "var": "webdata_pv_type",
    "content": ""
  },
  {
    "var": "webdata_rate_p",
    "content": ""
  },
  {
    "var": "webdata_now_p",
    "content": ""
  },
  {
    "var": "webdata_today_e",
    "content": ""
  },
  {
    "var": "webdata_total_e",
    "content": ""
  },
  {
    "var": "webdata_alarm",
    "content": ""
  },
  {
    "var": "webdata_utime",
    "content": ""
  },
  {
    "var": "cover_mid",
    "content": ""
  },
  {
    "var": "cover_ver",
    "content": ""
  },
  {
    "var": "cover_wmode",
    "content": ""
  },
  {
    "var": "cover_ap_ssid",
    "content": ""
  },
  {
    "var": "cover_ap_ip",
    "content": ""
  },
  {
    "var": "cover_ap_mac",
    "content": ""
  },
  {
    "var": "cover_sta_ssid",
    "content": ""
  },
  {
    "var": "cover_sta_rssi",
    "content": ""
  },
  {
    "var": "cover_sta_ip",
    "content": ""
  },
  {
    "var": "cover_sta_mac",
    "content": ""
  },
  {
    "var": "status_a",
    "content": ""
  },
  {
    "var": "status_b",
    "content": ""
  },
  {
    "var": "status_c",
    "content": ""
  }
]
```

## Deye variable description

| Variable name   | Type   | Description              |
|-----------------|--------|--------------------------|
| webdata_sn      | int    | serial no                |
| webdata_msvn    | ?      | Firmware version (main)  |
| webdata_ssvn    | ?      | Firmware version (slave) |
| webdata_pv_type | ?      | Inverter model           |
| webdata_rate_p  | ?      | Rated power              |
| webdata_now_p   | int    | Current power            |
| webdata_today_e | float  | Yield today              |
| webdata_total_e | float  | Total yield              |
| webdata_alarm   | int    | Alerts                   |
| webdata_utime   | int    | Last updated             |
| cover_mid       | int    | Device serial number     |
| cover_ver       | string | Firmware version         |
| cover_wmode     | string | ?                        |
| cover_ap_ssid   | string | Wireless SSID            |
| cover_ap_ip     | string | Wireless IP address      |
| cover_ap_mac    | string | Wireless MAC address     |
| cover_sta_ssid  | string | Router SSID              |
| cover_sta_rssi  | string | Signal Quality           |
| cover_sta_ip    | string | IP address               |
| cover_sta_mac   | string | MAC address              |
| status_a        | int    | Remote server A          |
| status_b        | int    | Remote server B          |
| status_c        | int    | Remote server C          |

## License

Open [LICENSE](https://github.com/turbopixel/deye-inverter-status/blob/master/LICENSE) file.