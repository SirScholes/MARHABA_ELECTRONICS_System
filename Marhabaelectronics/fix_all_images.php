<?php
require_once 'includes/config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Fix All Images</title>
    <style>
        body { font-family: Arial; padding: 20px; max-width: 800px; margin: 0 auto; }
        .success { color: green; }
        .error { color: red; }
        .step { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .btn { display: inline-block; background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Francoder Electronics - Fix All Images</h1>";

// Step 1: Create directories
echo "<div class='step'>";
echo "<h3>Step 1: Creating directories</h3>";
if (!is_dir('assets/images')) {
    mkdir('assets/images', 0777, true);
    echo "<p class='success'>✓ Created assets/images/ directory</p>";
} else {
    echo "<p class='success'>✓ assets/images/ directory exists</p>";
}
echo "</div>";

// Step 2: Create default image
echo "<div class='step'>";
echo "<h3>Step 2: Creating default placeholder</h3>";
$default_image = 'assets/images/default-product.jpg';
if (!file_exists($default_image)) {
    // Create a simple placeholder image using GD
    $img = imagecreatetruecolor(400, 300);
    $bg = imagecolorallocate($img, 52, 73, 94);
    $text_color = imagecolorallocate($img, 255, 255, 255);
    imagefill($img, 0, 0, $bg);
    $text = "Francoder";
    imagestring($img, 5, 160, 140, $text, $text_color);
    imagejpeg($img, $default_image, 80);
    imagedestroy($img);
    echo "<p class='success'>✓ Created default product image</p>";
} else {
    echo "<p class='success'>✓ Default image exists</p>";
}
echo "</div>";

// Step 3: Update all products with CDN images
echo "<div class='step'>";
echo "<h3>Step 3: Updating products with CDN images</h3>";

$cdn_images = [
    '1' => 'https://images.pexels.com/photos/18105/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=600', // Laptops
    '2' => 'https://images.pexels.com/photos/607812/pexels-photo-607812.jpeg?auto=compress&cs=tinysrgb&w=600', // Smartphones
    '3' => 'https://images.pexels.com/photos/3945659/pexels-photo-3945659.jpeg?auto=compress&cs=tinysrgb&w=600', // Gaming
    '4' => 'https://images.pexels.com/photos/3394669/pexels-photo-3394669.jpeg?auto=compress&cs=tinysrgb&w=600', // Audio
    '5' => 'https://images.pexels.com/photos/1029757/pexels-photo-1029757.jpeg?auto=compress&cs=tinysrgb&w=600' // Accessories
];

$stmt = $pdo->query("SELECT product_id, category_id FROM products");
$products = $stmt->fetchAll();
$count = 0;

foreach ($products as $product) {
    $category_id = $product['category_id'];
    $image_url = isset($cdn_images[$category_id]) ? $cdn_images[$category_id] : $cdn_images['1'];
    
    $update = $pdo->prepare("UPDATE products SET image_url = ? WHERE product_id = ?");
    $update->execute([$image_url, $product['product_id']]);
    $count++;
}

echo "<p class='success'>✓ Updated {$count} products with CDN images</p>";
echo "</div>";

// Step 4: Verify updates
echo "<div class='step'>";
echo "<h3>Step 4: Verification</h3>";
$stmt = $pdo->query("SELECT COUNT(*) as total, 
                     SUM(CASE WHEN image_url IS NULL OR image_url = '' THEN 1 ELSE 0 END) as no_image,
                     SUM(CASE WHEN image_url LIKE '%http%' THEN 1 ELSE 0 END) as cdn_images,
                     SUM(CASE WHEN image_url LIKE '%assets/images%' THEN 1 ELSE 0 END) as local_images
                     FROM products");
$stats = $stmt->fetch();

echo "<p>Total products: {$stats['total']}</p>";
echo "<p>Products with CDN images: {$stats['cdn_images']}</p>";
echo "<p>Products with local images: {$stats['local_images']}</p>";
echo "<p>Products with no image: {$stats['no_image']}</p>";
echo "</div>";

echo "<div style='margin-top: 30px;'>";
echo "<a href='index.php' class='btn'>View Homepage</a> ";
echo "<a href='products.php' class='btn'>View Products</a>";
echo "</div>";

echo "</body></html>";
?>