# payex-payment

# Payex Payment

== Description ==
With Payex, you can now accept payments from Malaysia & oversea customers via FPX, Cards (Visa/MC/UnionPay), EWallets, Instalments and Subscriptions (thru Cards & Bank Account). Integrated to major courier companies like GDex, J&T Express, Lalamove, MrSpeedy, and more!

Installation

A Payex payment package for Laravel 11.

## Installation

You can install the package via Composer:

```bash
composer require mutahirshah/payex-payment


Setup and Configuration

To set up with Payex:

1. Apply for a seller account at https://payex.io/ and your account will be processed within 1 business day.
2. Once your account is set up successfully, login to portal.payex.io and click on Setting on the left panel. Locate Secret.
3. enter different variable in .env file
4. Click on "Manage" once you have enabled Payex, enter your username with Payex (your registered email) and Secret.
5. Save changes.

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

PAYMENT_TYPE="card"


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
        $bill = array('amount'=>1,'payment_type'=>'card');
        $data    = $paymentProcessor->payexPayment($bill);
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



License - The MIT License (MIT)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.





```
