<?php
// referenties.php
header('Content-Type: application/json');

$dir = "portfolio/"; // map met je foto's
$images = glob($dir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

$result = [];
foreach ($images as $img) {
    $result[] = [
        "src" => $img,
        "name" => basename($img)
    ];
}

echo json_encode($result);
