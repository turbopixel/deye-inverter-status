<?php

/*
 * SETTINGS
 */
$ipAddress   = "192.168.1.19"; // Local inverter ip address
$credentials = "admin:admin"; // Default deye credentials: admin:admin

/*
 * Connect to the deye inverter and read the status.html page contents.
 */
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://{$ipAddress}/status.html");
curl_setopt($ch, CURLOPT_TIMEOUT, 20); //timeout after 30 seconds
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_USERPWD, $credentials);
$pageHtml             = curl_exec($ch);
$pageHeaderStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
curl_close($ch);

// HTTP Status 200 = OK
if ($pageHeaderStatusCode !== 200) {
  die("ERROR: Network connection failed");
}

/*
 * Find <script> Tag
 */
$doc = new DOMDocument();
$doc->loadHTML($pageHtml);
$selector = new DOMXPath($doc);
$result   = $selector->query('//script[@type="text/javascript"]');

$resultset = [];

if ($result instanceof DOMNodeList) {
  foreach ($result as $node) {
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
          if (str_starts_with($js, "var")) {

            // Fetch variable name and value
            $hasMatches = preg_match('/^(var) ([a-z_]+) = (.*);/m', $js, $matches);
            if (!$hasMatches) {
              continue;
            }

            $raw      = $matches[0] ?? NULL;
            $variable = $matches[2] ?? NULL; // Variable name
            $values   = trim($matches[3] ?? NULL); // Status Value

            // Remove all characters except specified
            $values = preg_replace("/[^A-Za-z0-9%.,_:]/", '', $values);

            $resultset[] = [
              // "raw"     => $raw,
              "var"     => $variable,
              "content" => $values
            ];
          }
        }
      }
    }
  }
} else {
  die("ERROR: DOMXPath failed");
}

echo json_encode($resultset);