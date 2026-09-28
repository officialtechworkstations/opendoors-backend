<?php
require_once 'include/reconfig.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("HTTP/1.0 404 Not Found");
    echo "Property Unavailable";
    exit;
}

$prop = $rstate->query("SELECT title, description, image, status FROM tbl_property WHERE id = " . $id)->fetch_assoc();

if (!$prop || $prop['status'] != 1) {
    header("HTTP/1.0 404 Not Found");
    echo "<!DOCTYPE html><html><head><title>Unavailable</title></head><body style='font-family: sans-serif; background-color: #f8f9fa;'><h1 style='text-align:center; padding: 50px; color: #333;'>Property Unavailable</h1><p style='text-align:center; color: #666;'>This property may have been removed or is no longer published.</p></body></html>";
    exit;
}

$title = htmlspecialchars($prop['title']);
$description = htmlspecialchars(strip_tags(str_replace('<br>', "\n", $prop['description'])));
$image = htmlspecialchars($prop['image']);

// Make image URL absolute if needed
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$currentUrl = $baseUrl . "/properties/" . $id;

if ($image && !preg_match("~^(?:f|ht)tps?://~i", $image)) {
    $image = rtrim($baseUrl, '/') . '/' . ltrim($image, '/');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <!-- Open Graph Meta Tags -->
    <meta property="og:url" content="<?= htmlspecialchars($currentUrl) ?>" />
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
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background-color: #f8f9fa; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        img { max-width: 100%; border-radius: 10px; margin-bottom: 20px; }
        h1 { font-size: 24px; color: #333; }
        p { color: #666; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($image): ?>
        <img src="<?= $image ?>" alt="<?= $title ?>">
        <?php endif; ?>
        <h1><?= $title ?></h1>
        <p><?= nl2br($description) ?></p>
        <p style="margin-top: 30px; color: #999; font-size: 14px;">Open this link on a mobile device with the OpenDoors app installed to view property details.</p>
    </div>
</body>
</html>
