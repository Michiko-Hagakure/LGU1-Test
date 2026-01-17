<?php
// Test web access to various directories
echo "<h1>Web Access Test</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .blocked{color:green;} .accessible{color:red;} .unknown{color:orange;}</style>";

echo "<p>This test checks if directories are properly blocked from web access.</p>";
echo "<p><span class='blocked'>GREEN = Properly blocked (good)</span> | <span class='accessible'>RED = Accessible (security risk)</span></p>";

$testUrls = [
    'Config Directory' => '/config/',
    'Vendor Directory' => '/vendor/',
    'Database Files' => '/database/',
    'Backup Files' => '/backup/',
    'Logs Directory' => '/logs/',
    'Docs Directory' => '/docs/',
    'Upload Directory' => '/uploads/',
    'Assets Directory' => '/assets/',
    'API Directory' => '/api/',
    'SuperAdmin Directory' => '/SuperAdmin/',
    'Test Directory' => '/test/',
    'Config File' => '/config/config.php',
    'Env File' => '/config/env.local.php',
    'Composer File' => '/composer.json',
    'Database SQL' => '/database/lgu1-auth_db.sql',
    'Autoloader' => '/vendor/autoload.php'
];

echo "<h2>Directory Access Test Results:</h2>";
echo "<div style='font-family:monospace;'>";

foreach ($testUrls as $name => $path) {
    $fullUrl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . "/.." . $path;
    echo "<div style='margin:5px 0;'>";
    echo "<strong>$name:</strong> ";
    echo "<a href='$fullUrl' target='_blank' style='margin-left:10px;'>$path</a>";
    echo " <span style='font-size:12px; color:#666;'>($fullUrl)</span>";
    echo "</div>";
}

echo "</div>";

echo "<h2>Instructions:</h2>";
echo "<ul>";
echo "<li>Click each link above to test access</li>";
echo "<li>Links should show 403 Forbidden or 404 Not Found (good security)</li>";
echo "<li>If you see directory listings or file contents, that's a security risk</li>";
echo "<li>Only /assets/ and /uploads/ should serve files (images, etc.)</li>";
echo "<li>/public/ directory should be accessible for login/register pages</li>";
echo "</ul>";

echo "<h2>Expected Results:</h2>";
echo "<ul>";
echo "<li><span class='blocked'>BLOCKED:</span> /config/, /vendor/, /database/, /backup/, /logs/, /docs/</li>";
echo "<li><span class='blocked'>BLOCKED:</span> .php files in protected directories</li>";
echo "<li><span class='accessible'>ACCESSIBLE:</span> /public/ (login, register pages)</li>";
echo "<li><span class='accessible'>ACCESSIBLE:</span> /assets/ (images, CSS, JS)</li>";
echo "<li><span class='accessible'>ACCESSIBLE:</span> /uploads/ (user uploaded images)</li>";
echo "</ul>";
?>