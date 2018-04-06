<?php


class Rivio {
    private static $api_base_url;
    private $api_key=NULL;
    private $secret_key=NULL;
    private $template_html_embed = NULL;
    private $template_html_reviews = NULL;
    private $template_html_reviews_and_rating_header = NULL;
    private $template_initjs_script_tag = NULL;
    private $template_product_stars = NULL;
    private $template_load_embed_js_on_click = NULL;

    function __construct($api_key = NULL,$secret_key = NULL,$options = NULL) {
        self::$api_base_url= (defined('RIVIO_API_BASE_URL') ? RIVIO_API_BASE_URL : 'https://api.getrivio.com/api');
        $this->api_key=$api_key;
        $this->secret_key=$secret_key;
        $this->options=$options;
        $this->set_templates();
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

    public function get_embed_js_on_click_js() {
        $scriptTag = $this->template_load_embed_js_on_click;

        return $scriptTag;
    }

    private function set_templates() {

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
        </div><div style="text-align:right"><a href="http://getrivio.com" style="opacity:0.8;font-size:11px;">Product reviews by Rivio</a></div>
        <?php
        $this->template_html_embed = ob_get_clean();

        //INITJS SCRIPT TAG
        ob_start();
        ?>
        <script type="text/javascript" async="" src="https://embed.getrivio.com/init.min.js?api_key={{api-key}}"></script>
        <?php
        $this->template_initjs_script_tag = ob_get_clean();

        //LOAD EMBED JS ON CLICK
        ob_start();
        ?>
        <script type="text/javascript">
            var script = document.createElement('script');
            var embedJsLoaded = false;

            var baseScriptSrc = "https://embed.getrivio.com/init.min.js?api_key=<?php echo $this->api_key ?>";

            document.onreadystatechange = function () {
                if (document.readyState === 'interactive') {
                    [].forEach.call(document.querySelectorAll('.rivio-embed-js-open'), function(el) {
                        el.addEventListener('click', function() {
                            if (!embedJsLoaded) {
                                script.src = baseScriptSrc;
                                if (el.dataset.openMethod && (
                                        el.dataset.openMethod === 'seller_reviews' ||
                                        el.dataset.openMethod === 'review_open' ||
                                        el.dataset.openMethod === 'rating_star_1' ||
                                        el.dataset.openMethod === 'rating_star_2' ||
                                        el.dataset.openMethod === 'rating_star_3' ||
                                        el.dataset.openMethod === 'rating_star_4' ||
                                        el.dataset.openMethod === 'rating_star_5'
                                    )
                                ) {
                                    script.src = script.src + '&open_method=' + el.dataset.openMethod;
                                }
                                document.head.appendChild(script);
                            }
                        })
                    })
                }
            };
        </script>
        <?php
        $this->template_load_embed_js_on_click = ob_get_clean();

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

        //EMBED HTML WITH RATING HEADER
        ob_start();
        ?>
        <div class="rivio-rating">
            <div class="rivio-rating-tabs">
                <div class="rivio-rating-tabs-product-reviews active"><svg aria-hidden="true" data-prefix="fas" data-icon="comments" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-comments fa-w-18"><path fill="currentColor" d="M224 358.857c-37.599 0-73.027-6.763-104.143-18.7-31.375 24.549-69.869 39.508-110.764 43.796a8.632 8.632 0 0 1-.89.047c-3.736 0-7.111-2.498-8.017-6.061-.98-3.961 2.088-6.399 5.126-9.305 15.017-14.439 33.222-25.79 40.342-74.297C17.015 266.886 0 232.622 0 195.429 0 105.16 100.297 32 224 32s224 73.159 224 163.429c-.001 90.332-100.297 163.428-224 163.428zm347.067 107.174c-13.944-13.127-30.849-23.446-37.46-67.543 68.808-64.568 52.171-156.935-37.674-207.065.031 1.334.066 2.667.066 4.006 0 122.493-129.583 216.394-284.252 211.222 38.121 30.961 93.989 50.492 156.252 50.492 34.914 0 67.811-6.148 96.704-17 29.134 22.317 64.878 35.916 102.853 39.814 3.786.395 7.363-1.973 8.27-5.467.911-3.601-1.938-5.817-4.759-8.459z" class=""></path></svg>Product reviews</div><div class="rivio-rating-tabs-seller-reviews rivio-embed-js-open" data-open-method="seller_reviews"><svg aria-hidden="true" data-prefix="fas" data-icon="comment" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-comment fa-w-18"><path fill="currentColor" d="M576 240c0 115-129 208-288 208-48.3 0-93.9-8.6-133.9-23.8-40.3 31.2-89.8 50.3-142.4 55.7-5.2.6-10.2-2.8-11.5-7.7-1.3-5 2.7-8.1 6.6-11.8 19.3-18.4 42.7-32.8 51.9-94.6C21.9 330.9 0 287.3 0 240 0 125.1 129 32 288 32s288 93.1 288 208z" class=""></path></svg>Seller reviews</div>
            </div>
            <div class="rivio-rating-controls">
                <div class="rivio-rating-controls-stars">
                    {{header-rating-stars}}
                </div>
                <div class="rivio-rating-controls-title">
                    {{rate-title}}
                </div>
                <br>
                <div class="rivio-rating-title">
                    You didn't rate this product yet. <a href="javascript:" class="rivio-embed-js-open" data-open-method="review_open">Write a review</a>
                </div>
            </div>
        </div>
        <?php
        $this->template_html_reviews_and_rating_header = ob_get_clean();
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

    public function stars_html($productId, $reviews_translate = NULL) {

        if (!$reviews_translate) {
            $reviews_translate = 'reviews';
        }

        $reviewsJson =  $this->product_reviews_json($productId);

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

    public function product_reviews_html_and_rating_header($productId) {
        $reviewsJson = $this->product_reviews_json($productId);

        // As default, show rating header
        $embedSellerReviewsEnabled = 1;

        if (isset($reviewsJson['embed_seller_reviews_enabled'])) {
            $embedSellerReviewsEnabled = intval($reviewsJson['embed_seller_reviews_enabled']);
        }

        $reviews = $reviewsJson['reviews'];

        $reviewAverage = $reviewsJson['review_average'];

        $template = '';

        $html = $this->template_html_reviews;

        if ($embedSellerReviewsEnabled) {
            $ratingTemplate = $this->template_html_reviews_and_rating_header;

            if (sizeof($reviews) > 0) {
                $ratingTemplate = str_replace('{{rate-title}}', round($reviewAverage) . ' by ' . sizeof($reviews) . ' customer(s)', $ratingTemplate);
                $headerRating = '';
                $i = 0;

                while ($i < 5) {
                    if ($i < $reviewAverage) {
                        $headerRating .= '<div class="rivio-rating-controls-stars-star full rivio-embed-js-open" data-open-method="rating_star_'. ($i + 1) .'"><svg aria-hidden="true" data-prefix="far" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="rivio-star-empty"><path fill="currentColor" d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z" class=""></path></svg><svg aria-hidden="true" data-prefix="fas" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="rivio-star-full"><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" class=""></path>	</svg></div>';
                    } else {
                        $headerRating .= '<div class="rivio-rating-controls-stars-star rivio-embed-js-open" data-open-method="rating_star_'. ($i + 1) .'"><svg aria-hidden="true" data-prefix="far" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="rivio-star-empty"><path fill="currentColor" d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z" class=""></path></svg><svg aria-hidden="true" data-prefix="fas" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="rivio-star-full"><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" class=""></path>	</svg></div>';
                    }
                    $i++;
                }

                $ratingTemplate = str_replace('{{header-rating-stars}}', $headerRating, $ratingTemplate);
            } else {
                $ratingTemplate = str_replace('{{rate-title}}', 'Be the first one to review this item', $ratingTemplate);
                $ratingTemplate = str_replace('{{header-rating-stars}}', '<div class="rivio-rating-controls-stars-star"><svg aria-hidden="true" data-prefix="far" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="rivio-star-empty"><path fill="currentColor" d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z" class=""></path></svg><svg aria-hidden="true" data-prefix="fas" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="rivio-star-full"><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" class=""></path></svg></div><div class="rivio-rating-controls-stars-star"><svg aria-hidden="true" data-prefix="far" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="rivio-star-empty"><path fill="currentColor" d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z" class=""></path></svg><svg aria-hidden="true" data-prefix="fas" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="rivio-star-full"><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" class=""></path></svg></div><div class="rivio-rating-controls-stars-star"><svg aria-hidden="true" data-prefix="far" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="rivio-star-empty"><path fill="currentColor" d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z" class=""></path></svg><svg aria-hidden="true" data-prefix="fas" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="rivio-star-full"><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" class=""></path></svg></div><div class="rivio-rating-controls-stars-star"><svg aria-hidden="true" data-prefix="far" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="rivio-star-empty"><path fill="currentColor" d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z" class=""></path></svg><svg aria-hidden="true" data-prefix="fas" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="rivio-star-full"><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" class=""></path></svg></div><div class="rivio-rating-controls-stars-star"><svg aria-hidden="true" data-prefix="far" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="rivio-star-empty"><path fill="currentColor" d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z" class=""></path></svg><svg aria-hidden="true" data-prefix="fas" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="rivio-star-full"><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" class=""></path></svg></div>', $ratingTemplate);
            }

            $template .= $ratingTemplate;
        }

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

    public function refresh_json_cache($date = NULL) {

        $url = self::$api_base_url."/products/json_cache?api_key=".$this->api_key."&secret_key=".$this->secret_key;

        if (!is_null($date)) {
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

        if ($cacheAvailable) {
            // Products, getting review(s) in the last 24 hours
            $date = date("Y-m-d_H:i:s", strtotime('-1 day'));

            $this->refresh_json_cache($date);
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

}

?>