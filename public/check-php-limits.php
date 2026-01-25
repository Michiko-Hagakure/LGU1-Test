Now we're going to create an API post request for those who want to reserve a facility, similar to the ones I sent for our API integration in the Infrastructure Project Management, but the ones there should be the things we need when reserving a facility, which is on our citizen side, whatever is needed.

But you won't erase what we've already done in reserving citizens, it's just another reservation strategy thru an API.<?php
/**
 * PHP Limits Checker Tool
 * Access via: https://facilities.local-government-unit-1-ph.com/check-php-limits.php
 * DELETE THIS FILE AFTER USE!
 */

echo "<html><head><title>PHP Limits Check</title>";
echo "<style>body{font-family:Arial,sans-serif;padding:20px;max-width:800px;margin:0 auto;}";
echo "table{border-collapse:collapse;width:100%;}th,td{border:1px solid #ddd;padding:12px;text-align:left;}";
echo "th{background:#1a5632;color:white;}.warning{color:red;font-weight:bold;}.ok{color:green;}</style></head><body>";

echo "<h1>PHP Upload/POST Limits</h1>";
echo "<table>";
echo "<tr><th>Setting</th><th>Current Value</th><th>Recommended</th><th>Status</th></tr>";

$settings = [
    'post_max_size' => ['current' => ini_get('post_max_size'), 'recommended' => '50M'],
    'upload_max_filesize' => ['current' => ini_get('upload_max_filesize'), 'recommended' => '50M'],
    'max_file_uploads' => ['current' => ini_get('max_file_uploads'), 'recommended' => '20'],
    'max_execution_time' => ['current' => ini_get('max_execution_time'), 'recommended' => '300'],
    'max_input_time' => ['current' => ini_get('max_input_time'), 'recommended' => '300'],
    'memory_limit' => ['current' => ini_get('memory_limit'), 'recommended' => '256M'],
];

function parseSize($size) {
    $unit = strtoupper(substr($size, -1));
    $value = (int)$size;
    switch($unit) {
        case 'G': return $value * 1024 * 1024 * 1024;
        case 'M': return $value * 1024 * 1024;
        case 'K': return $value * 1024;
        default: return $value;
    }
}

foreach ($settings as $name => $data) {
    $currentBytes = parseSize($data['current']);
    $recommendedBytes = parseSize($data['recommended']);
    $status = $currentBytes >= $recommendedBytes ? '<span class="ok">OK</span>' : '<span class="warning">TOO LOW</span>';
    echo "<tr><td>{$name}</td><td>{$data['current']}</td><td>{$data['recommended']}</td><td>{$status}</td></tr>";
}

echo "</table>";

echo "<h2>PHP Info Summary</h2>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Server API:</strong> " . php_sapi_name() . "</p>";
echo "<p><strong>php.ini Path:</strong> " . php_ini_loaded_file() . "</p>";

$additionalInis = php_ini_scanned_files();
if ($additionalInis) {
    echo "<p><strong>Additional INI files:</strong> " . $additionalInis . "</p>";
}

echo "<h2>Recommendations</h2>";
echo "<p>If limits are too low, contact your hosting provider to increase them, or check if you can edit:</p>";
echo "<ul>";
echo "<li><code>" . php_ini_loaded_file() . "</code></li>";
if ($additionalInis) {
    echo "<li>Additional INI files listed above</li>";
}
echo "</ul>";

echo "<p style='color:red;font-weight:bold;margin-top:30px;'>⚠️ DELETE THIS FILE AFTER USE!</p>";
echo "</body></html>";
