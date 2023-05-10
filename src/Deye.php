<?php

declare(strict_types = 1);

namespace Deye;

use DOMDocument;
use DOMNodeList;
use DOMXPath;
use Exception;

/**
 * Deye Inverter Status.
 *
 * Simple php library to query the inverter status information.
 *
 * @version 1.1.0
 * @author Nico Hemkes
 * @package Deye
 * @copyright Copyright 2023 Nico Hemkes
 * @license https://github.com/turbopixel/deye-inverter-status/blob/master/LICENSE
 * @link https://hemk.es
 */
class Deye {

  /**
   * Inverter ip address
   *
   * @var string
   */
  public string $inverterIp = "";

  /**
   * Inverter credentials (default admin:admin)
   *
   * @var string
   */
  public string $credentials = "admin:admin";

  /**
   * @return string
   */
  public function getInverterIp() : string {
    return $this->inverterIp;
  }

  /**
   * @param string $inverterIp
   *
   * @return Deye
   */
  public function setInverterIp(string $inverterIp) : Deye {
    $this->inverterIp = $inverterIp;

    return $this;
  }

  /**
   * @return string
   */
  public function getCredentials() : string {
    return $this->credentials;
  }

  /**
   * @param string $credentials
   *
   * @return Deye
   */
  public function setCredentials(string $credentials) : Deye {
    $this->credentials = $credentials;

    return $this;
  }

  /**
   * Connect to the deye inverter and read the status.html page contents.
   */
  protected function requestInverter() : string {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://{$this->inverterIp}/status.html");
    curl_setopt($ch, CURLOPT_TIMEOUT, 20); //timeout after 30 seconds
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_USERPWD, $this->credentials);
    $pageContent = curl_exec($ch);
    $httpStatus  = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
    curl_close($ch);

    // HTTP Status 200 = OK
    if ($httpStatus !== 200) {
      throw new Exception("ERROR: Network connection failed");
    }

    if (!$pageContent) {
      throw new Exception("ERROR: curl failed");
    }

    return $pageContent;
  }

  /**
   * Find <script> tags
   *
   * @return DOMNodeList
   * @throws Exception
   */
  protected function parseDocument() : DOMNodeList {
    $domDocument = new DOMDocument();
    $domDocument->loadHTML($this->requestInverter());

    $selector  = new DOMXPath($domDocument);
    $domResult = $selector->query('//script[@type="text/javascript"]');
    if (!$domResult instanceof DOMNodeList) {
      throw new Exception("ERROR: Parsing DOM failed");
    }

    return $domResult;
  }

  /**
   * Find matching JavaScript variables.
   *
   * @param string $js
   *
   * @return array|false
   */
  protected function parseInverterStatus(string $js) : array|false {

    // Fetch variable name and value
    // var webdata_now_p = "65" --> [$0, $1, $2, $3]
    $hasMatches = preg_match('/^(var) ([a-z_]+) = (.*);/m', $js, $matches);
    if (!$hasMatches) {
      return false;
    }

    // $raw      = $matches[0] ?? NULL; // raw javascript variable
    $variable = $matches[2] ?? NULL; // variable name
    $values   = trim($matches[3] ?? NULL); // status Value

    // Remove all characters except specified
    $values = preg_replace("/[^A-Za-z0-9%.,_:]/", '', $values);

    return [
      // "raw"     => $raw,
      "var"     => $variable,
      "content" => $values
    ];
  }

  /**
   * Connects to the inverter status page, temporarily downloads the
   * content and parses the document for status information.
   *
   * @return array
   * @throws Exception
   */
  public function inverterStatus() : array {
    $parsedDocument  = $this->parseDocument();
    $inverterDataSet = [];

    foreach ($parsedDocument as $node) {
      if (is_object($node) && isset($node->textContent)) {

        // Search for webdata_ content
        if (!str_contains($node->textContent, "webdata_")) {
          continue;
        }

        // Split newline into array
        $jsCode = preg_split("/\r\n|\n|\r/", $node->textContent);
        if (is_array($jsCode)) {

          // Walk through each line
          foreach ($jsCode as $line => $js) {

            // Find javascript variables
            if (!str_starts_with($js, "var")) {
              continue;
            }

            $inverterVariable = $this->parseInverterStatus($js);
            if (!$inverterVariable) {
              continue;
            }

            $inverterDataSet[] = $inverterVariable;
          }
        }
      }
    }

    return $inverterDataSet;
  }

}