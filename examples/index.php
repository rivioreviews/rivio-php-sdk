<html>
<head>
    <title>Rivio PHP SDK Examples</title>
</head>
<body>
    <h1>
        Rivio PHP SDK Examples
    </h1>
    <p>
        For testing, you will need to copy the <b>config.sample.php</b> file in the example folder, as <b>config.php</b> and insert your <b>Rivio API key</b> and <b>Secret key</b>.
        You can get them, from <b><a href="http://dashboard.getrivio.com/dashboard/settings/business" target="_blank">here</a></b>.
    </p>

    <h2>
        Client side (JavaScript) rendered samples
    </h2>


    <h3>
        <a href="product_reviews_widget.php">Product reviews widget</a> (rendered with JavaScript)
    </h3>
    <p>
        An example how to use the Rivio product reviews widget.
    </p>

    <h3>
        <a href="stars_widget.php">Stars Widget</a>
    </h3>
    <p>
        Use the Stars Widget to show an average rating of the product with the number of ratings. Include the product id in your embedded code snippet, and you're all set.
    </p>

    <h2>Server side (PHP) rendered samples</h2>

    <h3>
        <a href="product_reviews_html.php">Product reviews HTML</a> (rendered with PHP)
    </h3>
    <p>
        Insert Rivio reviews for a product in HTML format.
    </p>

    <h3>
        <a href="stars_html.php">Stars HTML</a> (rendered with PHP)
    </h3>
    <p>
        Insert Rivio stars widget for a product in HTML format. (Reviews read from cache)
    </p>

    <h3>
        <a href="product_reviews_json.php">Product Reviews JSON</a> (rendered with PHP)
    </h3>
    <p>
        Get the product reviews in a JSON format.
    </p>

    <h2>Hybrid (PHP & JavaScript) render samples</h2>

    <h3>
        <a href="product_reviews_widget_with_html_reviews.php">Embed Widget with HTML reviews</a>
    </h3>
    <p>
        Insert Rivio reviews for a product with the embed widget, and the HTML reviews. Useful to make your reviews visible for search engines for example.
    </p>

    <h2>
        <a href="register_post_purchase.php">Register Post-purchase Email</a>
    </h2>
    <p>
        After a purchase in your store, this code will send a "Post purchase email" to the buyer to write a review about it.<br>You can also configure the email sending <b><a href="https://dashboard.reev.io/dashboard/email/settings" target="_blank">here<a/></b>.
    </p>

    <h2>
        <a href="cache_cron_job.php">Server side review caching</a>
    </h2>
    <p>
        An example how to create a cron job, which updates the cache of reviews for products, getting review(s) for the last 24 hours.
    </p>

</body>
</html>