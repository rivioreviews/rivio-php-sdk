<?php

require_once(__DIR__ . "/bootstrap.php");

$rivio_reviews_html = $rivio->product_reviews_html('1492411012');
$rivio_stars = $rivio->stars_html('1492411012');

$rivio_reviews_json = $rivio->product_reviews_json('1492411012');

?>
<html>
<head>
    <title>Google rich snippet - Rivio PHP SDK example</title>
    <link rel="stylesheet" href="assets/review.css">
</head>
<body>
<h1>Google rich snippet with Rivio</h1>
<div class="rivio-product" style="font-family: Arial, Helvetica, sans-serif">
    <h3 style="display: inline">SmartPhone S6</h3>
    <?php echo $rivio_stars?>
    <button style="background: #FB4056; border-radius: 5px; border: none; padding: 8px 12px; color: #fff; font-weight: bold; font-size: 16px; cursor: pointer;">
        $ 299.99
    </button> Buy now
    <p>
        Awesome smart phone. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce volutpat quis tortor ac malesuada. Aliquam a justo porttitor, vestibulum justo non, ullamcorper eros. Proin elit ligula, lobortis quis viverra eget, fermentum quis lacus. Sed bibendum bibendum sapien vel blandit. Nullam quis neque pellentesque arcu ultrices dapibus. Ut in massa at libero efficitur sollicitudin. Nullam semper facilisis porttitor. Sed sed mi at enim consequat dictum quis a urna. Nullam lacinia vel odio sed fermentum. Mauris in pulvinar felis. Ut pulvinar sem in mi sollicitudin elementum. Vivamus quis urna quis erat commodo tincidunt.
    </p>
</div>
<div class="rivio-reviews" style="max-width: 600px; margin-top: 50px;">
    <?php echo $rivio_reviews_html; ?>
</div>
<h2>
    The Google rich snippet:
</h2>
<p>
    It also inserted to this page, check it by viewing the page source.
</p>
<pre style="background: #eeeeee; padding: 8px;">
&lt;script type="application/ld+json"&gt;
{
  "@context": "http://schema.org/",
  "@type": "Product",
  "name": "SmartPhone S6",
  "image": "http://www.example.com/smart_phone_6.jpg",
  "description": "Awesome smart phone. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce volutpat quis tortor ac malesuada. Aliquam a justo porttitor, vestibulum justo non, ullamcorper eros. Proin elit ligula, lobortis quis viverra eget, fermentum quis lacus. Sed bibendum bibendum sapien vel blandit. Nullam quis neque pellentesque arcu ultrices dapibus. Ut in massa at libero efficitur sollicitudin. Nullam semper facilisis porttitor. Sed sed mi at enim consequat dictum quis a urna. Nullam lacinia vel odio sed fermentum. Mauris in pulvinar felis. Ut pulvinar sem in mi sollicitudin elementum. Vivamus quis urna quis erat commodo tincidunt.",
  "brand": {
    "@type": "Thing",
    "name": "Smart"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?php echo $rivio_reviews_json['review_average'] ?>",
    "ratingCount": "<?php echo $rivio_reviews_json['review_count'] ?>"
  },
  "offers": {
    "@type": "AggregateOffer",
    "lowPrice": "299.99",
    "highPrice": "299.99",
    "priceCurrency": "USD"
  }
}
&lt;/script&gt;
</pre>

<!-- Google Rich Snippet -->
<script type="application/ld+json">
{
  "@context": "http://schema.org/",
  "@type": "Product",
  "name": "SmartPhone S6",
  "image": "http://www.example.com/smart_phone_6.jpg",
  "description": "Awesome smart phone. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce volutpat quis tortor ac malesuada. Aliquam a justo porttitor, vestibulum justo non, ullamcorper eros. Proin elit ligula, lobortis quis viverra eget, fermentum quis lacus. Sed bibendum bibendum sapien vel blandit. Nullam quis neque pellentesque arcu ultrices dapibus. Ut in massa at libero efficitur sollicitudin. Nullam semper facilisis porttitor. Sed sed mi at enim consequat dictum quis a urna. Nullam lacinia vel odio sed fermentum. Mauris in pulvinar felis. Ut pulvinar sem in mi sollicitudin elementum. Vivamus quis urna quis erat commodo tincidunt.",
  "brand": {
    "@type": "Thing",
    "name": "Smart"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?php echo $rivio_reviews_json['review_average'] ?>",
    "ratingCount": "<?php echo $rivio_reviews_json['review_count'] ?>"
  },
  "offers": {
    "@type": "AggregateOffer",
    "lowPrice": "299.99",
    "highPrice": "299.99",
    "priceCurrency": "USD"
  }
}
</script>
</body>
</html>