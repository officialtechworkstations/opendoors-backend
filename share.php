<?php
require_once 'include/reconfig.php';

$type = isset($_GET['type']) ? $_GET['type'] : 'property';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$title = 'OpenDoors';
$description = 'Discover amazing properties on OpenDoors.';
$image = '';

$deepLink = '';

if ($id > 0) {
    if ($type === 'reel') {
        // Fetch reel details to get property ID
        $reel = $rstate->query("SELECT prop_id FROM tbl_reels WHERE id = " . $id)->fetch_assoc();
        if ($reel && $reel['prop_id']) {
            $prop_id = $reel['prop_id'];
            $prop = $rstate->query("SELECT title, description, image FROM tbl_property WHERE id = " . $prop_id)->fetch_assoc();
            if ($prop) {
                $title = htmlspecialchars($prop['title']) . ' - Reel';
                // Strip tags to ensure description is clean text
                $description = htmlspecialchars(strip_tags(str_replace('<br>', "\n", $prop['description'])));
                $image = htmlspecialchars($prop['image']);
               c // Replace with actual scheme if different
            }
        }
    } else {
        // Default to property
        $prop = $rstate->query("SELECT title, description, image FROM tbl_property WHERE id = " . $id)->fetch_assoc();
        if ($prop) {
            $title = htmlspecialchars($prop['title']);
            // Strip tags to ensure description is clean text
            $description = htmlspecialchars(strip_tags(str_replace('<br>', "\n", $prop['description'])));
            $image = htmlspecialchars($prop['image']);
            $deepLink = "opendoors://property/" . $id; // Replace with actual scheme if different
        }
    }
}

// Make image URL absolute if needed
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$currentUrl = $baseUrl . $_SERVER['REQUEST_URI'];

if ($image && !preg_match("~^(?:f|ht)tps?://~i", $image)) {
    $image = $baseUrl . '/' . ltrim($image, '/');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <!-- Open Graph Meta Tags -->
    <meta property="og:url" content="<?= $currentUrl ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= $title ?>" />
    <meta property="og:description" content="<?= $description ?>" />
    <?php if ($image): ?>
    <meta property="og:image" content="<?= $image ?>" />
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?= $title ?>" />
    <meta name="twitter:description" content="<?= $description ?>" />
    <meta name="twitter:image" content="<?= $image ?>" />
    <?php endif; ?>

    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        img {
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 24px;
            color: #333;
        }
        p {
            color: #666;
            line-height: 1.5;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($image): ?>
        <img src="<?= $image ?>" alt="<?= $title ?>">
        <?php endif; ?>
        <h1><?= $title ?></h1>
        <p><?= nl2br($description) ?></p>

        <?php if ($deepLink): ?>
        <a href="<?= $deepLink ?>" class="btn" id="open-app-btn">Open in App</a>
        
        <script>
            // Attempt to automatically open the deep link if the user hasn't interacted
            window.onload = function() {
                setTimeout(function() {
                    window.location.href = "<?= $deepLink ?>";
                }, 1000);
            };
        </script>
        <?php endif; ?>
    </div>
</body>
</html>
