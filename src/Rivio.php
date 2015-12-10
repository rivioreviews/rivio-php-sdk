<?php


class Rivio {
    private static $api_base_url='https://api.reev.io/api';
    private $api_key=NULL;
    private $secret_key=NULL;
    private $business_id=NULL;

    function __construct($api_key = NULL,$secret_key = NULL,$business_id = NULL){
        $this->api_key=$api_key;
        $this->secret_key=$secret_key;
        $this->business_id=$business_id;
    }

    public function register_postpurchase_email(
        $order_id,
        $ordered_date,
        $customer_email,
        $customer_first_name,
        $product_id,
        $product_name,
        $product_description = NULL,
        $product_url = NULL,
        $product_image_url =  NULL,
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
            "product_description"=>$product_description,
            "product_url"=>$product_url,
            "product_image_url"=>$product_image_url,
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


    public function get_embed_html(){
        $embedHTML="TODO";
        /*<div class="reevio"
									     data-reevio-api-key="api-key"
									     data-reevio-product-id="15264"
									     data-reevio-name="name"
									     data-reevio-lang="en"
									     data-reevio-url="http://example.com/product/22"
									     data-reevio-type="Smart Phone"
									     data-reevio-image-url="http://example.com/images/product/22.png"
									     data-reevio-description="&lt;h2&gt;Férfi futónadrág &lt;/h2&gt;fekete + sárga &lt;br /&gt;&lt;br /&gt;Hátsó cipzáras zsebbel, kopásálló, lapos varrásokkal és fényvisszaverő részékkel. Mínuszban is komfortot ad. Kényelmes, testhezálló szabásával tökéletesen illeszkedik. A meleget benntartó, de a párát kiengedő speciális, lélegző anyagból készült."
									     data-reevio-barcode="1234567890123"
									     data-reevio-brand="Samsung"
									     data-reevio-price="19900">
									</div><div style="text-align:right"><a href="http://reev.io" style="opacity:0.8;font-size:11px;">Product reviews by Reevio</a></div>
									</div>
								  </td>
                                </tr>
<script type="text/javascript">
            (function() {
                var rvio = document.createElement('script');
                rvio.type = 'text/javascript';
                rvio.async = true;
                rvio.src = 'https://embed.reev.io/init.min.js';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(rvio);
            })();
</script>	*/
        return $embedHTML;
    }
}