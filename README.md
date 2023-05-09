# deye-inverter-status

Reads the current [deye inverter](https://www.deyeinverter.com/) status and returns a json object.

This script accesses the inverter and reads out the status.html page, which contains all
necessary information from the inverter.

Works with **DEYE SUN600 / SUN800**.

## Installation

Download the script, adjust the variables (see comment SETTINGS) and execute it on a
local computer or server.

> It is important that the inverter is connected to the same network.

## Execute

The script should be executed on the console. PHP must be installed!

```bash
php -f deye.php
```

**Output**

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

### Variable description

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