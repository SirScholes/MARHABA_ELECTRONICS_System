<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

echo "<h1>Updating Product Images in Database</h1>";

// Get all products
$stmt = $pdo->query("SELECT product_id, product_name, brand FROM products");
$products = $stmt->fetchAll();

$image_map = [
    // Map products to specific images based on category/brand
    'laptop' => 'laptop.jpg',
    'notebook' => 'laptop.jpg',
    'ultrabook' => 'laptop.jpg',
    'rog' => 'laptop.jpg',
    'legion' => 'laptop.jpg',
    'smartphone' => 'smartphone.jpg',
    'phone' => 'smartphone.jpg',
    'galaxy' => 'smartphone.jpg',
    'pixel' => 'smartphone.jpg',
    'headphone' => 'headphones.jpg',
    'earbud' => 'headphones.jpg',
    'audio' => 'headphones.jpg',
    'bose' => 'headphones.jpg',
    'sony' => 'headphones.jpg',
    'keyboard' => 'keyboard.jpg',
    'mouse' => 'mouse.jpg',
    'monitor' => 'monitor.jpg',
    'display' => 'monitor.jpg',
    'tablet' => 'tablet.jpg',
    'ipad' => 'tablet.jpg',
    'surface' => 'tablet.jpg',
    'watch' => 'smartwatch.jpg',
    'garmin' => 'smartwatch.jpg',
    'fitbit' => 'smartwatch.jpg',
    'console' => 'console.jpg',
    'gaming' => 'console.jpg',
    'camera' => 'camera.jpg',
    'gopro' => 'camera.jpg',
    'speaker' => 'speaker.jpg',
    'sonos' => 'speaker.jpg',
    'marshall' => 'speaker.jpg',
    'ssd' => 'ssd.jpg',
    'storage' => 'ssd.jpg'
];

$updated = 0;
$default_image = 'assets/images/default-product.jpg';

foreach ($products as $product) {
    $image_filename = $default_image;
    $product_name_lower = strtolower($product['product_name']);
    $brand_lower = strtolower($product['brand'] ?? '');
    
    // Find matching image
    foreach ($image_map as $keyword => $filename) {
        if (strpos($product_name_lower, $keyword) !== false || strpos($brand_lower, $keyword) !== false) {
            $image_filename = 'assets/images/' . $filename;
            break;
        }
    }
    
    // Update database
    $stmt = $pdo->prepare("UPDATE products SET image_url = ? WHERE product_id = ?");
    $stmt->execute([$image_filename, $product['product_id']]);
    
    echo "<p>✓ Updated: {$product['product_name']} → " . basename($image_filename) . "</p>";
    $updated++;
}

echo "<h2 style='color: green;'>✓ Updated {$updated} products with local images</h2>";
echo "<p><a href='index.php'>View Homepage</a> | <a href='products.php'>View Products</a></p>";
?>