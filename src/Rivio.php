<?php


class Rivio {
    private static $api_base_url='https://api.getrivio.com/api';
    private $api_key=NULL;
    private $secret_key=NULL;
    private $template_html_embed=NULL;
    private $template_initjs_script_tag=NULL;
    private $template_product_stars=NULL;

    function __construct($api_key = NULL,$secret_key = NULL,$options = NULL){
        $this->api_key=$api_key;
        $this->secret_key=$secret_key;
        $this->options=$options;
        $this->set_templates();
    }

    public function register_postpurchase_email(
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
    ){
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

        return $this->register_postpurchase_emails($orders);
    }

    public function register_postpurchase_emails($orders){
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
        }else{
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


    public function get_embed_widget(
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
        $server_side_rendering = false
    ){

        $server_side_html = $server_side_rendering ? $this->get_reviews_html($product_id) : '';

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
        $template = str_replace("{{server-side-html}}", $server_side_html ,$template);

        return $template;
    }

    public function get_init_js(){
        $scriptTag =  $this->template_initjs_script_tag;
        $scriptTag = str_replace("{{api-key}}", $this->api_key ,$scriptTag);

        return $scriptTag;
    }

    private function set_templates(){


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
            {{server-side-html}}
        </div><div style="text-align:right"><a href="http://getrivio.com" style="opacity:0.8;font-size:11px;">Product reviews by Rivio</a></div>
        <?php
        $this->template_html_embed = ob_get_clean();


        //INITJS SCRIPT TAG
        ob_start();
        ?>
        <script type="text/javascript" async="" src="https://embed.getrivio.com/init.min.js?api_key={{api-key}}"></script>
        <?php
        $this->template_initjs_script_tag = ob_get_clean();

    }

    public function get_stars_widget($product_id){

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

    public function get_rating($product_id){
        $result = Rivio::fetchUrl("https://api.getrivio.com/api/review/product-ratings?api_key=".$this->api_key."&product_ids=".$product_id);
        $json_result = json_decode($result,true);
        if($json_result === null){
            throw new Exception('Server responded with invalid json format');
        } else {
            return $json_result[0];
        }

    }

    public function get_reviews_json($productId) {

        //$url = "https://api.getrivio.com/api/products/json_cache?api_key=".$this->api_key."&secret_key=".$this->secret_key."&product_id=".$productId;
        $url = "http://9ec5a104.ngrok.io/api/products/json_cache?api_key=".$this->api_key."&secret_key=".$this->secret_key."&product_id=".$productId;
        $result = Rivio::fetchUrl($url);
        $json_result = json_decode($result,true);

        if ($json_result === null) {
            throw new Exception('Server responded with invalid json format');
        }

        return $json_result;
    }

    public function get_reviews_html($productId) {

        $json_result = $this->get_reviews_json($productId);

        $shopItem = $json_result[0];

        $reviews = $shopItem['reviews'];

        $template = '';

        $htmlPath = __DIR__."/assets/review.html";
        $htmlFile = fopen($htmlPath, "r");
        $html = fread($htmlFile, filesize($htmlPath));
        fclose($htmlFile);

        foreach ($reviews as $review) {

            $reviewTemplate = $html;

            $ratingStars = '';

            $i = 0;
            while ($i < 5) {
                if ($i < $review['rating']) {
                    $ratingStars .= '<span class="rivio-star full"></span>';
                } else {
                    $ratingStars .= '<span class="rivio-star empty"></span>';
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

    public function get_json_cache() {

        $url = "https://api.getrivio.com/api/products/json_cache?api_key=".$this->api_key."&secret_key=".$this->secret_key;

        $result = Rivio::fetchUrl($url);
        $products = json_decode($result,true);

        if ($products === null) {
            throw new Exception('Server responded with invalid json format');
        }

        $path = __DIR__ . "/rivio_cache";

        if (isset($this->options) && isset($this->options['cache']) && isset($this->options['cache']['path']) && (isset($this->options['cache']['type']) && $this->options['cache']['type'] == "file_storage")) {
            $path = $this->options['cache']['path'];
        }

        foreach ($products as $product) {
            $jsonCacheFile = fopen($path."/".$product['product_id'].".json", "w+");
            fwrite($jsonCacheFile, json_encode($product));
            fclose($jsonCacheFile);
        }

        return $products;
    }
}

?>