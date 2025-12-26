<?php
// Test file to check asset paths
require_once __DIR__ . '/app/autoload.php';

echo "<h1>Asset Path Test</h1>";
echo "<p>Base URL: " . $_SERVER['HTTP_HOST'] . "</p>";
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";

$testPaths = [
    'AdminLTE-2/dist/css/AdminLTE.min.css',
    'AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css',
    'AdminLTE-2/bower_components/font-awesome/css/font-awesome.min.css',
];

echo "<h2>Asset Paths:</h2>";
echo "<ul>";
foreach ($testPaths as $path) {
    $fullPath = asset($path);
    $fileExists = file_exists(__DIR__ . '/public/' . $path);
    $status = $fileExists ? '✅ EXISTS' : '❌ NOT FOUND';
    echo "<li>";
    echo "<strong>$path</strong><br>";
    echo "Full URL: <a href='$fullPath' target='_blank'>$fullPath</a><br>";
    echo "File exists: $status<br>";
    echo "</li>";
}
echo "</ul>";

echo "<h2>Test CSS Links:</h2>";
echo "<link rel='stylesheet' href='" . asset('AdminLTE-2/dist/css/AdminLTE.min.css') . "'>";
echo "<link rel='stylesheet' href='" . asset('AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') . "'>";
echo "<div class='container' style='margin-top: 20px;'>";
echo "<div class='alert alert-success'>If you see this styled, CSS is working!</div>";
echo "</div>";
?>
