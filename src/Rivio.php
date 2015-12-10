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

    public function get_embed_html(){

    }

    public function register_postpurchase_email(){
        $order=array(
            "order_id"=> "1492411012",
            "ordered_date"=>"2015-09-28T09:16:16-04:00",
            "customer_email"=>"example@email.com",
            "customer_first_name"=>"Joe",
            "product_id"=>"1621557380",
            "product_name"=>"Example product",
            "product_barcode"=>"545241324753",
            "product_category"=>"Smart phone",
            "product_url"=>"http://reevio-dani-test.myshopify.com/products/nexus-5-lg-d821",
            "product_image_url"=>"https://cdn.shopify.com/s/files/1/0981/5786/products/415925.lg-e960-nexus-4-16gb_1024x1024.jpg?v=1441359580",
            "product_description"=>"This is the product description",
            "product_brand"=>"Example brand",
            "product_price"=>"18.00"
        );

        $orders=array($order);

        return $this->register_postpurchase_emails($orders);
    }

    public function register_postpurchase_emails($orders){
        $postBody=array(
            "orders"=> $orders
        );

        // Setup cURL
        $ch = curl_init(static::$api_base_url.'/postpurchase?business_id='.$this->business_id.'&reevio_secret_key='.$this->secret_key.'');
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
        if($response === FALSE){
            throw new Exception(curl_error($ch));
        }

        // Decode the response
        $responseData = json_decode($response, TRUE);

        return $responseData;
    }
}