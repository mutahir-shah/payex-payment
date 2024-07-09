# payex-payment

# Payex Payment

A Payex payment package for Laravel 11.

## Installation

You can install the package via Composer:

```bash
composer require mutahirshah/payex-payment

this package only currently works for only 1 end point the payments

api/v1/PaymentIntents

# configuration
If using Laravel 11, your package service provider should be automatically discovered. If not, you can manually add the service provider to config/app.php:


'providers' => [
    // Other Service Providers
    Mutahirshah\PayexPayment\PayexPaymentServiceProvider::class,
],



php artisan vendor:publish --tag=payex-config

php artisan vendor:publish --provider="Mutahirshah\PayexPayment\PayexPaymentServiceProvider" --tag=config

Enviromental variables setting

PAYMENT_GATEWAY_API_USERNAME="examples@gmail.com"
PAYMENT_GATEWAY_API_SECRET="yourpassword"
PAYMENT_GATEWAY_SANDBOX=false
RETURN_URL="http://localhost:8085/shahge/laravel-api/public/callback"
CALLBACK_URL="http://localhost:8085/shahge/laravel-api/public/callback"
ACCEPT_URL="http://localhost:8085/shahge/laravel-api/public/callback"
REJECT_URL="http://localhost:8085/shahge/laravel-api/public/callback"


You have to allow the CSRF token for payex the call back will through error like not found etc. or not allowed.

How to implement the code in your controller is below

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mutahirshah\PayexPayment\PayexPaymentProcessor;

class HomeController extends Controller
{
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $paymentProcessor = new PayexPaymentProcessor();
        $bill = array('amount'=>1);  

        $data = $paymentProcessor->payexPayment($bill);
        $result = json_decode($data,true);
        if($result['error'] == false){            
            header("Location: " . $result['url']);exit;
        } 
    }


    public function returnFunction(Request $request)
    {
        dd($request->all());
    }
}


