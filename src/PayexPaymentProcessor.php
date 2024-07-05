<?php

namespace Mutahirshah\PayexPayment;

use Exception;
use Illuminate\Support\Facades\Log;

class PayexPaymentProcessor
{
    protected $apiKey;
    protected $apiSecret;
    protected $sandbox;
    protected $auth;
    protected $apiUrl;
    protected $return_url;
    protected $callback_url;
    protected $accept_url;
    protected $reject_url;
    protected $payex_currency;
    protected $country_code;


    public function __construct()
    {
        $this->apiKey = config('payex.api_username');
        $this->apiSecret = config('payex.api_secret');
        $this->sandbox = config('payex.sandbox');
        $this->return_url = config('payex.return_url');
        $this->callback_url = config('payex.callback_url');
        $this->accept_url = config('payex.accept_url');
        $this->reject_url = config('payex.reject_url');
        $this->payex_currency = config('payex.payex_currency');
        $this->country_code = config('payex.country_code');
        // $auth = base64_encode("email:password");
        $this->auth = base64_encode($this->apiKey . ':' . $this->apiSecret);
        $this->apiUrl = 'https://api.payex.io/';
        if ($this->sandbox) {
            $this->apiUrl = 'https://sandbox-payexapi.azurewebsites.net/';
        }
    }

    public function process($amount)
    {
        // Implement your payment processing logic
        return "Processed payment of $amount.";
    }


    public function payexPayment($data = [])
    {
        /* FIRT AUTHENTICATE*/
        $token            = $this->getPayexToken();
        /* END AUTHORIZATION*/

        $currency         = !empty($data['currency']) ? $data['currency'] : $this->payex_currency;
        $country          = !empty($data['country_code']) ? $data['country_code'] : $this->country_code;
        $customer_name    = !empty($data['customer_name']) ? $data['customer_name'] : 'No Customer Name Provided';
        $phone_number     = !empty($data['phone_number']) ? $data['phone_number'] : '12345678971';
        $discription      = !empty($data['discription']) ? $data['discription'] : 'Payment description was send empty';
        $address          = !empty($data['address']) ? $data['address'] : 'Address was send empty.';
        $reference_number = !empty($data['reference_number']) ? $data['reference_number'] : '43200';
        $customer_email   = !empty($data['customer_email']) ? $data['customer_email'] : 'mutahiricup@gmail.com';
        $postcode         = !empty($data['postcode']) ? $data['postcode'] : '43200';
        $city             = !empty($data['city']) ? $data['city'] : 'Bandar Makh';
        $state            = !empty($data['state']) ? $data['state'] : 'SGR';
        $amount           = $data['amount'] * 100;
 
        if ($token) {
            try {

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL =>   'https://api.payex.io/api/v1/PaymentIntents',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '[{"amount": ' . $amount . ',"currency": "' . $currency . '","customer_name": "' . $customer_name . '","email": "' . $customer_email . '","contact_number": "' . $phone_number . '","address": "' . $address . '","postcode": "' . $postcode . '","city": "' . $city . '","state":"' . $state . '","country":"' . $country . '","description": "' . $discription . '" ,"reference_number": "' . $reference_number . '","return_url": "' . $this->return_url  . '","callback_url": "' . $this->callback_url . '","accept_url": "' . $this->accept_url . '","reject_url": "' . $this->reject_url . '"}]',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer ' . $token . '',
                        'Content-Type: application/json',
                        'Cookie: ARRAffinity=d30cc046ce27286e641d8e18d378ae394091489768cf8cf9b268164b427296dd; ARRAffinitySameSite=d30cc046ce27286e641d8e18d378ae394091489768cf8cf9b268164b427296dd'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $result = json_decode($response);
                $status = $result->result[0]->error; 
                $error = false;
                if ($result->status == '00') {
                    $status                     = $result->message;
                } else {
                    $status  = $result->message;
                    $error   = true;
                }
                $data = [
                    'error'   => $error,
                    'message' => $status,
                    'url'     => $result->result[0]->url
                ]; 
                return json_encode($data);
            } catch (Exception $e) {
                $log = "";
                $log .= "Caught exception: " . $e->getMessage() . PHP_EOL;
                $data = ['error' => true, 'message' => $log, 'url' => $this->reject_url];
            }
        } else {
            $data = ['error' => true, 'message' => 'Authentication error!', 'url' => $this->reject_url];
            return json_encode($data);
        }
    }

    private function getPayexToken()
    {
        try {

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->apiUrl . 'api/Auth/Token',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic ' . $this->auth,
                    'Cookie: ARRAffinity=d30cc046ce27286e641d8e18d378ae394091489768cf8cf9b268164b427296dd; ARRAffinitySameSite=d30cc046ce27286e641d8e18d378ae394091489768cf8cf9b268164b427296dd'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($response);

            if (isset($result->token)) {
                return $result->token;
            }

            throw new Exception('Token not found in response');
        } catch (Exception $e) {
            return false;
        }
    }


    public function createPayment($amount, $currency, $returnUrl)
    {
        // Implement the logic to create a payment with the gateway API
    }

    public function verifyPayment($paymentId)
    {
        // Implement the logic to verify a payment with the gateway API
    }
}