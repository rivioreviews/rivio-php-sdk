<?php


class Rivio {
    private static $api_base_url;
    private $api_key=NULL;
    private $secret_key=NULL;
    private $template_html_embed=NULL;
    private $template_html_reviews=NULL;
    private $template_initjs_script_tag=NULL;
    private $template_product_stars=NULL;

    function __construct($api_key = NULL,$secret_key = NULL,$options = NULL) {
        self::$api_base_url= (defined('RIVIO_API_BASE_URL') ? RIVIO_API_BASE_URL : 'https://api.getrivio.com/api');
        $this->api_key=$api_key;
        $this->secret_key=$secret_key;
        $this->options=$options;
        $this->set_templates($options);
    }

    public function register_post_purchase_email(
        $order_id,
        $ordered_date,
        $customer_email,
        $customer_first_name,
        $product_id,
        $product_name,
        $product_url = NULL,
        $product_image_url =  NULL,
        $product_description = NULL,
        $product_barcode = NULL,
        $product_category = NULL ,
        $product_brand = NULL,
        $product_price  = NULL
    ) {
        $order=array(
            "order_id"=> $order_id,
            "ordered_date"=>$ordered_date,
            "customer_email"=>$customer_email,
            "customer_first_name"=>$customer_first_name,
            "product_id"=>$product_id,
            "product_name"=>$product_name,
            "product_url"=>$product_url,
            "product_image_url"=>$product_image_url,
            "product_description"=>$product_description,
            "product_barcode"=>$product_barcode,
            "product_category"=>$product_category,
            "product_brand"=>$product_brand,
            "product_price"=>$product_price
        );

        $orders=array($order);

        return $this->register_post_purchase_emails($orders);
    }

    public function register_post_purchase_email_multiple_product(
        $order_id,
        $ordered_date,
        $customer_email,
        $customer_first_name,
        $products
    ) {
        $order = array(
            "order_id"=> $order_id,
            "ordered_date"=>$ordered_date,
            "customer_email"=>$customer_email,
            "customer_first_name"=>$customer_first_name,
            "products"=>$products
        );

        $orders = array($order);

        return $this->register_post_purchase_emails($orders);
    }

    public function register_post_purchase_emails($orders) {

        $postBody=array(
            "orders"=> $orders
        );

        $url=static::$api_base_url . '/postpurchase?api_key=' . $this->api_key . '&secret_key=' . $this->secret_key;

        if(function_exists('curl_version')) {
            // Setup cURL
            $ch = curl_init($url);

            curl_setopt_array($ch, array(
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
                CURLOPT_POSTFIELDS => json_encode($postBody)
            ));

            // Send the request
            $response = curl_exec($ch);

            // Check for errors
            if ($response === FALSE) {
                throw new Exception(curl_error($ch));
            }

            // Decode the response
            $responseData = json_decode($response, TRUE);
        } else {
            // Create the context for the request
            $context = stream_context_create(array(
                'http' => array(
                    // http://www.php.net/manual/en/context.http.php
                    'method' => 'POST',
                    'header' => "Content-Type: application/json\r\n",
                    'content' => json_encode($postBody)
                )
            ));

            // Send the request
            $response = @file_get_contents($url, FALSE, $context);

            // Check for errors
            if($response === FALSE){
                throw new Exception('file_get_contents error, maybe your api_key or secret_key is invalid');
            }

            // Decode the response
            $responseData = json_decode($response, TRUE);
        }

        if(isset($responseData["code"])){
            throw new Exception($responseData["message"],$responseData["code"]);
        }

        return $responseData;
    }


    public function product_reviews_widget(
        $product_id,
        $product_name,
        $product_url = "",
        $product_image_url =  "",
        $product_description = "",
        $product_barcode = "",
        $product_category = "" ,
        $product_brand = "",
        $product_price  = "",
        $lang = "en",
        $reviews_html = ""
    ) {
        $template=$this->template_html_embed;

        $template = str_replace("{{api-key}}", $this->api_key ,$template);
        $template = str_replace("{{product-id}}", $product_id ,$template);
        $template = str_replace("{{product-name}}", $product_name ,$template);
        $template = str_replace("{{lang}}", $lang ,$template);
        $template = str_replace("{{product-url}}", $product_url ,$template);
        $template = str_replace("{{product-image-url}}", $product_image_url ,$template);
        $template = str_replace("{{product-description}}", $product_description ,$template);
        $template = str_replace("{{product-barcode}}", $product_barcode ,$template);
        $template = str_replace("{{product-category}}", $product_category ,$template);
        $template = str_replace("{{product-brand}}", $product_brand ,$template);
        $template = str_replace("{{product-price}}", $product_price ,$template);
        $template = str_replace("{{reviews-html}}", $reviews_html ,$template);

        return $template;
    }

    public function get_init_js() {

        $scriptTag =  $this->template_initjs_script_tag;
        $scriptTag = str_replace("{{api-key}}", $this->api_key ,$scriptTag);

        return $scriptTag;
    }

    private function set_templates($options) {

        //EMBED HTML
        ob_start();
        ?>
        <div class="rivio-embed"
             data-rivio-api-key="{{api-key}}"
             data-rivio-product-id="{{product-id}}"
             data-rivio-name="{{product-name}}"
             data-rivio-lang="{{lang}}"
             data-rivio-url="{{product-url}}"
             data-rivio-image-url="{{product-image-url}}"
             data-rivio-description="{{product-description}}"
             data-rivio-barcode="{{product-barcode}}"
             data-rivio-type="{{product-category}}"
             data-rivio-brand="{{product-brand}}"
             data-rivio-price="{{product-price}}">
            {{reviews-html}}
        </div>
        <?php
        $this->template_html_embed = ob_get_clean();

        if (array_key_exists('hide_rivio_footer', $options) && $options['hide_rivio_footer']) {
            $this->template_html_embed .= "<div style=\"text-align:right\"><a href=\"http://getrivio.com\" style=\"opacity:0.8;font-size:11px;\">Product reviews by Rivio</a></div>";
        }
        //INITJS SCRIPT TAG
        ob_start();
        ?>
        <script type="text/javascript" async="" src="https://embed.getrivio.com/init.min.js?api_key={{api-key}}"></script>
        <?php
        $this->template_initjs_script_tag = ob_get_clean();

        //EMBED HTML
        ob_start();
        ?>
        <div class="rivio-reviews">
            <hr>
            <div class="rivio-reviews-review">
                <div class="rivio-reviews-review-left">
                    <div class="rivio-reviews-review-avatar">
                        <p>
                            {{user-capital}}
                        </p>
                    </div>
                </div>
                <div class="rivio-reviews-review-body">
                    <div class="rivio-reviews-review-body-date">
                        <p>
                            {{review-date}}
                        </p>
                    </div>
                    <div class="rivio-reviews-review-body-username">
                        <p>
                            {{user-name}}
                        </p>
                    </div>
                    <div class="rivio-reviews-review-body-rating">
                        <p>
                            {{rating-stars}}
                        </p>
                    </div>
                    <div class="rivio-reviews-review-body-title">
                        <p>
                            {{title}}
                        </p>
                    </div>
                    <div class="rivio-reviews-review-body-body">
                        <p>
                            {{body}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $this->template_html_reviews = ob_get_clean();
    }

    public function stars_widget($product_id) {

        // PRODUCT STARS
        ob_start();
        ?>

        <div class="rivio-stars-widget" data-rivio-stars-widget-product-id="{{product_id}}"></div>

        <?php

        $template = $this->template_product_stars = ob_get_clean();
        $template = str_replace("{{product_id}}", $product_id ,$template);

        return $template;
    }

    private function fetchUrl($url) {

        $allowUrlFopen = preg_match('/1|yes|on|true/i', ini_get('allow_url_fopen'));

        if ($allowUrlFopen) {
            return file_get_contents($url);
        } elseif (function_exists('curl_init')) {
            $c = curl_init($url);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
            $contents = curl_exec($c);
            curl_close($c);
            if (is_string($contents)) {
                return $contents;
            }
        }

        return false;
    }

    public function stars_html($productId, $reviews_translate = NULL, $withReviewsOnly = false) {

        if (!$reviews_translate) {
            $reviews_translate = 'reviews';
        }

        $reviewsJson =  $this->product_reviews_json($productId);

        if ($withReviewsOnly && $reviewsJson['review_count'] * 1 === 0) {
            return false;
        }

        $starsTemplate = "<div class='rivio-reviews-stars'>";

        $i = 0;
        while ($i < 5) {
            if ($i < $reviewsJson['review_average']) {
                $starsTemplate .= '<span class="rivio-reviews-star" style="color: #ffd200; text-shadow: 0 1px 0 #cb9500; font-size: 20px;">&#9733;</span>';
            } else {
                $starsTemplate .= '<span class="rivio-reviews-star" style="color: #ffd200; text-shadow: 0 1px 0 #cb9500; font-size: 20px;">&#9734;</span>';
            }
            $i++;
        }

        $starsTemplate .= "<span>".$reviewsJson['review_count']."</span> " . $reviews_translate;
        $starsTemplate .= "</div>";

        return $starsTemplate;
    }

    public function product_rating($product_id) {

        $result = Rivio::fetchUrl(self::$api_base_url."/review/product-ratings?api_key=".$this->api_key."&product_ids=".$product_id);
        $json_result = json_decode($result,true);

        if($json_result === null){
            throw new Exception('Server responded with invalid json format');
        } else {
            return $json_result[0];
        }
    }

    public function product_reviews_json($productId) {

        if (!isset($this->options)) {
            throw new Exception('Options array cache is not set. Example array:
                $options = [
                    "cache" => [
                        "type" => "file_storage",
                        "path" => __DIR__ . "/rivio_cache"
                    ],
                ];
            ');
        }

        if (isset($this->options['cache']) && isset($this->options['cache']['path']) && isset($this->options['cache']['type'])) {
            $jsonFilePath = $this->options['cache']['path'];
        } else {
            throw new Exception('Cache path or type  not set in options, example:
                $options = [
                    "cache" => [
                        "type" => "file_storage",
                        "path" => __DIR__ . "/rivio_cache"
                    ],
            ];');
        }

        if ($this->options['cache']['type'] !== "file_storage") {
            throw new Exception('Only file storage type caching is supported for now.');
        }

        $jsonFilePath = $jsonFilePath . '/' . $productId . '.json';

        $reviewsJson = '{"product_id":"'.$productId.'","review_count":"0","review_average":"0","reviews":[]}';

        if (file_exists($jsonFilePath)) {
            $jsonFile = fopen($jsonFilePath, "r");
            $reviewsJson = fread($jsonFile, filesize($jsonFilePath));
            fclose($jsonFile);
        }

        return json_decode($reviewsJson, 1);
    }

    public function product_reviews_html($productId) {

        $reviewsJson = $this->product_reviews_json($productId);

        if (count($reviewsJson['reviews']) === 0) {
            return false;
        }

        $reviews = $reviewsJson['reviews'];

        $template = '';

        $html = $this->template_html_reviews;

        foreach ($reviews as $review) {

            $reviewTemplate = $html;

            $ratingStars = '';

            $i = 0;
            while ($i < 5) {
                if ($i < $review['rating']) {
                    $ratingStars .= '<span class="rivio-reviews-star full"></span>';
                } else {
                    $ratingStars .= '<span class="rivio-reviews-star empty"></span>';
                }
                $i++;
            }

            $reviewDate = strtotime($review['created_at']);
            $reviewDate = date("n/j/y", $reviewDate);

            $reviewTemplate = str_replace('{{user-capital}}', substr($review['user_name'],0,1), $reviewTemplate);
            $reviewTemplate = str_replace('{{review-date}}', $reviewDate, $reviewTemplate);
            $reviewTemplate = str_replace('{{user-name}}', $review['user_name'], $reviewTemplate);
            $reviewTemplate = str_replace('{{rating-stars}}', $ratingStars, $reviewTemplate);
            $reviewTemplate = str_replace('{{title}}', $review['title'], $reviewTemplate);
            $reviewTemplate = str_replace('{{body}}', $review['body'], $reviewTemplate);

            $template .= $reviewTemplate;
        }

        return $template;
    }

    public function refresh_json_cache($date = NULL, $cacheSettings) {

        date_default_timezone_set('UTC');

        $url = self::$api_base_url."/products/json_cache?api_key=".$this->api_key."&secret_key=".$this->secret_key;

        $lastFullRefreshDate = $cacheSettings['last_full_refresh'];
        $yesterday = date("Y-m-d_H:i:s", strtotime('-1 day'));

        $fullRefreshRequired = $lastFullRefreshDate < $yesterday;

        if (!is_null($date) && !$fullRefreshRequired) {
            $url .= "&last_updated_at_is_gt=".$date;
        }

        $result = Rivio::fetchUrl($url);

        $products = [];

        if (!$result) {
            return $products;
        }

        $products = json_decode($result,true);

        if ($products === null) {
            throw new Exception('Server responded with invalid json format');
        }

        if (isset($this->options) && isset($this->options['cache']) && isset($this->options['cache']['path']) && (isset($this->options['cache']['type']) && $this->options['cache']['type'] == "file_storage")) {
            $path = $this->options['cache']['path'];
        } else {
            throw new Exception('Options array cache is not set. Example array:
                $options = [
                    "cache" => [
                        "type" => "file_storage",
                        "path" => __DIR__ . "/rivio_cache"
                    ],
                ];
            ');
        }

        foreach ($products as $product) {
            $jsonCacheFile = fopen($path."/".$product['product_id'].".json", "w+");
            fwrite($jsonCacheFile, json_encode($product));
            fclose($jsonCacheFile);
        }

        return $products;
    }

    public function execute_cron() {

        $cacheAvailable = $this->initCache();

        $cacheSettings = $this->getCacheSettings();

        if ($cacheAvailable) {
            // Products, getting review(s) in the last 24 hours
            $date = date("Y-m-d_H:i:s", strtotime('-1 day'));

            $this->refresh_json_cache($date, $cacheSettings);
        }
    }

    public function initCache() {

        $cacheAvailable = false;

        if (isset($this->options) && isset($this->options['cache']) && isset($this->options['cache']['path']) && (isset($this->options['cache']['type']) && $this->options['cache']['type'] == "file_storage")) {
            $cachePath = $this->options['cache']['path'];
        } else {
            throw new Exception('Options array cache is not set. Example array:
                $options = [
                    "cache" => [
                        "type" => "file_storage",
                        "path" => __DIR__ . "/rivio_cache"
                    ],
                ];
            ');
        }

        if (is_readable($cachePath)) {
            $handle = opendir($cachePath);
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $cacheAvailable = true;
                }
            }
        }

        if (!$cacheAvailable) {
            $this->refresh_json_cache();
        }

        return $cacheAvailable;
    }

    public function getCacheSettings() {
        $jsonFilePath = $this->options['cache']['path'];
        $jsonFilePath = $jsonFilePath . '/rivio-cache-settings.json';

        date_default_timezone_set('UTC');

        if (file_exists($jsonFilePath)) {
            $jsonFile = fopen($jsonFilePath, 'r');
            $cacheSettings = fread($jsonFile, filesize($jsonFilePath));
            $cacheSettings = json_decode($cacheSettings, true);
            fclose($jsonFile);
        } else {
            $cacheSettingsJsonFile = fopen($jsonFilePath, 'w+');
            $date = date("Y-m-d_H:i:s");
            $cacheSettings = json_encode(array("last_full_refresh" => $date));
            fwrite($cacheSettingsJsonFile, $cacheSettings);
            fclose($cacheSettingsJsonFile);
        }

        return $cacheSettings;
    }

}

?>