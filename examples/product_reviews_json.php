<?php

require_once(__DIR__ . "/bootstrap.php");

$rivio_reviews_json = $rivio->product_reviews_json('1492411012');
$rivio_reviews_json = json_encode($rivio_reviews_json, JSON_PRETTY_PRINT);
?>
<html>
<head>
    <title>Product reviews JSON</title>
</head>
<body>
<h1>Product reviews JSON</h1>
<pre style="background: #eeeeee; padding: 8px;">
<?php echo $rivio_reviews_json;?>
</pre>
</body>
</html>
