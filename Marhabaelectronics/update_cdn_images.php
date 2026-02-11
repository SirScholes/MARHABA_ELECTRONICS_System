<?php
require_once 'includes/config.php';

echo "<h1>Updating Products with CDN Image URLs</h1>";

// Array of CDN image URLs (Cloudinary/Pexels/Unsplash)
$cdn_images = [
    'laptop' => 'https://images.pexels.com/photos/18105/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=600',
    'gaming-laptop' => 'https://images.pexels.com/photos/2115256/pexels-photo-2115256.jpeg?auto=compress&cs=tinysrgb&w=600',
    'smartphone' => 'https://images.pexels.com/photos/607812/pexels-photo-607812.jpeg?auto=compress&cs=tinysrgb&w=600',
    'headphones' => 'https://images.pexels.com/photos/3394669/pexels-photo-3394669.jpeg?auto=compress&cs=tinysrgb&w=600',
    'keyboard' => 'https://images.pexels.com/photos/777001/pexels-photo-777001.jpeg?auto=compress&cs=tinysrgb&w=600',
    'mouse' => 'https://images.pexels.com/photos/2115217/pexels-photo-2115217.jpeg?auto=compress&cs=tinysrgb&w=600',
    'monitor' => 'https://images.pexels.com/photos/1029757/pexels-photo-1029757.jpeg?auto=compress&cs=tinysrgb&w=600',
    'tablet' => 'https://images.pexels.com/photos/1334597/pexels-photo-1334597.jpeg?auto=compress&cs=tinysrgb&w=600',
    'watch' => 'https://images.pexels.com/photos/437037/pexels-photo-437037.jpeg?auto=compress&cs=tinysrgb&w=600',
    'console' => 'https://images.pexels.com/photos/3945659/pexels-photo-3945659.jpeg?auto=compress&cs=tinysrgb&w=600',
    'speaker' => 'https://images.pexels.com/photos/1037992/pexels-photo-1037992.jpeg?auto=compress&cs=tinysrgb&w=600'
];

// Get all products
$stmt = $pdo->query("SELECT product_id, product_name, category_id FROM products");
$products = $stmt->fetchAll();

$category_map = [
    1 => 'laptop',        // Laptops
    2 => 'smartphone',    // Smartphones
    3 => 'console',       // Gaming Consoles
    4 => 'headphones',    // Audio Equipment
    5 => 'monitor'        // Computer Accessories
];

$updated = 0;

foreach ($products as $product) {
    $product_name_lower = strtolower($product['product_name']);
    $category_id = $product['category_id'];
    $image_url = '';
    
    // Determine which image to use
    if (strpos($product_name_lower, 'gaming') !== false && strpos($product_name_lower, 'laptop') !== false) {
        $image_url = $cdn_images['gaming-laptop'];
    } elseif (strpos($product_name_lower, 'laptop') !== false) {
        $image_url = $cdn_images['laptop'];
    } elseif (strpos($product_name_lower, 'smartphone') !== false || strpos($product_name_lower, 'phone') !== false) {
        $image_url = $cdn_images['smartphone'];
    } elseif (strpos($product_name_lower, 'headphone') !== false || strpos($product_name_lower, 'earbud') !== false) {
        $image_url = $cdn_images['headphones'];
    } elseif (strpos($product_name_lower, 'keyboard') !== false) {
        $image_url = $cdn_images['keyboard'];
    } elseif (strpos($product_name_lower, 'mouse') !== false) {
        $image_url = $cdn_images['mouse'];
    } elseif (strpos($product_name_lower, 'monitor') !== false || strpos($product_name_lower, 'display') !== false) {
        $image_url = $cdn_images['monitor'];
    } elseif (strpos($product_name_lower, 'tablet') !== false || strpos($product_name_lower, 'ipad') !== false) {
        $image_url = $cdn_images['tablet'];
    } elseif (strpos($product_name_lower, 'watch') !== false) {
        $image_url = $cdn_images['watch'];
    } elseif (strpos($product_name_lower, 'console') !== false) {
        $image_url = $cdn_images['console'];
    } elseif (strpos($product_name_lower, 'speaker') !== false) {
        $image_url = $cdn_images['speaker'];
    } elseif (isset($category_map[$category_id])) {
        $image_url = $cdn_images[$category_map[$category_id]];
    } else {
        $image_url = 'https://images.pexels.com/photos/356056/pexels-photo-356056.jpeg?auto=compress&cs=tinysrgb&w=600'; // Default electronics
    }
    
    // Update database
    $stmt = $pdo->prepare("UPDATE products SET image_url = ? WHERE product_id = ?");
    $stmt->execute([$image_url, $product['product_id']]);
    
    echo "<p>✓ Updated: {$product['product_name']}</p>";
    $updated++;
}

echo "<h2 style='color: green;'>✓ Updated {$updated} products with CDN images</h2>";
?>