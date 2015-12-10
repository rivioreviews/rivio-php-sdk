<?php


class Rivio {
    private static $api_base_url='https://api.reev.io/api';
    private $api_key=NULL;
    private $secret_key=NULL;
    private $embed_template=NULL;

    function __construct($api_key = NULL,$secret_key = NULL){
        $this->api_key=$api_key;
        $this->secret_key=$secret_key;
        $this->set_embed_template();

        //echo $this->embed_template;

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

        $url=static::$api_base_url . '/postpurchase?api_key=' . $this->api_key . '&secret_key=' . $this->secret_key . '';
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


    public function get_embed_html(
            $product_id,
            $product_name,
            $product_url = "",
            $product_image_url =  "",
            $product_description = "",
            $product_barcode = "",
            $product_category = "" ,
            $product_brand = "",
            $product_price  = "",
            $lang="en"
    ){
        $template=$this->embed_template;


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

        return $template;
    }

    private function set_embed_template(){
        ob_start();
?>
<div class="reevio"
    data-reevio-api-key="{{api-key}}"
    data-reevio-product-id="{{product-id}}"
    data-reevio-name="{{product-name}}"
    data-reevio-lang="{{lang}}"
    data-reevio-url="{{product-url}}"
    data-reevio-image-url="{{product-image-url}}"
    data-reevio-description="{{product-description}}"
    data-reevio-barcode="{{product-barcode}}"
    data-reevio-type="{{product-category}}"
    data-reevio-brand="{{product-brand}}"
    data-reevio-price="{{product-price}}">
</div><div style="text-align:right"><a href="http://reev.io" style="opacity:0.8;font-size:11px;">Product reviews by Reevio</a></div>
<script type="text/javascript">
    (function() {
        var rvio = document.createElement('script');
        rvio.type = 'text/javascript';
        rvio.async = true;
        rvio.src = 'https://embed.reev.io/init.min.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(rvio);
    })();
</script>
<?php
        $this->embed_template = ob_get_clean();
    }
}