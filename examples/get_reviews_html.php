<?php

require_once(__DIR__ . "/bootstrap.php");

$rivio_reviews_html = $rivio->get_reviews_html('1492411012');

?>
<html>
<head>
    <title>Embed module - Rivio PHP SDK example</title>
    <link rel="stylesheet" href="assets/review.css">
</head>
<body>
<h1>Rivio Embed Module</h1>
<?php echo $rivio_reviews_html; ?>
</body>
</html>
