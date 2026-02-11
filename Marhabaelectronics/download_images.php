<?php
require_once 'includes/config.php';

echo "<h1>Downloading Product Images</h1>";

// Array of free-to-use Unsplash images for electronics
$image_urls = [
    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600&h=400&fit=crop', // Laptop
    'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&h=400&fit=crop', // Smartphone
    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=400&fit=crop', // Headphones
    'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=600&h=400&fit=crop', // Keyboard
    'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=600&h=400&fit=crop', // Monitor
    'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=600&h=400&fit=crop', // Tablet
    'https://images.unsplash.com/photo-1527814050087-3793815479db?w=600&h=400&fit=crop', // Mouse
    'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=600&h=400&fit=crop', // Smart Watch
    'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=600&h=400&fit=crop', // Gaming Console
    'https://images.unsplash.com/photo-1558655146-9f40138edfeb?w=600&h=400&fit=crop', // Camera
    'https://images.unsplash.com/photo-1586210579191-33b45e38fa2c?w=600&h=400&fit=crop', // Speaker
    'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=600&h=400&fit=crop'  // SSD
];

$filenames = [
    'laptop.jpg',
    'smartphone.jpg',
    'headphones.jpg',
    'keyboard.jpg',
    'monitor.jpg',
    'tablet.jpg',
    'mouse.jpg',
    'smartwatch.jpg',
    'console.jpg',
    'camera.jpg',
    'speaker.jpg',
    'ssd.jpg'
];

// Create directory if it doesn't exist
if (!is_dir('assets/images')) {
    mkdir('assets/images', 0777, true);
    echo "<p>Created images directory</p>";
}

// Download images
$downloaded = 0;
for ($i = 0; $i < count($image_urls); $i++) {
    $filename = 'assets/images/' . $filenames[$i];
    
    // Download image
    $image_content = @file_get_contents($image_urls[$i]);
    
    if ($image_content !== false) {
        file_put_contents($filename, $image_content);
        echo "<p style='color: green;'>✓ Downloaded: {$filenames[$i]}</p>";
        $downloaded++;
    } else {
        echo "<p style='color: red;'>✗ Failed to download: {$filenames[$i]}</p>";
    }
}

// Create default placeholder image
$default_image = 'assets/images/default-product.jpg';
if (!file_exists($default_image)) {
    copy('https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=300&fit=crop', $default_image);
    echo "<p style='color: green;'>✓ Created default product image</p>";
}

echo "<h2>Downloaded {$downloaded} images</h2>";
echo "<p><a href='fix_product_images.php'>Step 2: Update product images in database</a></p>";
?>