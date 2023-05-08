<?php

/*
 * Settings
 */
$ipAddress   = "192.168.1.19";
$credentials = "admin:admin"; // Default deye credentials: admin:admin

/*
 * Connect to the deye inverter and read the status.html page contents.
 */
$auth     = base64_encode($credentials);
$context  = stream_context_create([
  "http" => [
    "header" => "Authorization: Basic {$auth}"
  ]
]);
$pageHtml = file_get_contents("http://{$ipAddress}/status.html", false, $context);

/*
 * Find <script> Tag
 */
$doc = new DOMDocument();
$doc->loadHTML($pageHtml);
$selector = new DOMXPath($doc);
$result   = $selector->query('//script[@type="text/javascript"]');

$resultset = [];
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
          preg_match('/^(var) ([a-z_]+) = (.*);/m', $js, $matches);

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

echo json_encode($resultset);