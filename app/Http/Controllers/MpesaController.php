<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\MpesaTransaction;

class MpesaController extends Controller
{
    // Generation of Passwords
    public function lipaNaMpesaPassword()
    {
        // TIMESTAMP
        $timestamp = Carbon::now()->format('YmdHis');
        // PASSKEY
        $passKey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
        // BUSINESSSHORTCODE
        $businessShortCode = '174379';
        // GENERATE PASSWORD
        $mpesaPassword = base64_encode($businessShortCode . $passKey . $timestamp);
        return $mpesaPassword;
    }

    public function newAccessToken()
    {
        $consumer_key = 'gRmSH9RKAXIf53rggZWLT4R4NsclYHtKUbAM0MODHiKr3scp';
        $consumer_secret = "5Ll7AsNFlbVcBiUBGIum9Nagddm8yp9nneThvlieLjaYPxJCWvWtVvoYrgLTPU6i";
        $credentials = base64_encode($consumer_key . ":" . $consumer_secret);
        $url = "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic " . $credentials, "Content-Type:application/json"));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        $response_object = json_decode($curl_response);
        curl_close($curl);

        if (isset($response_object->access_token)) {
            // Return the access token string if it exists
            return $response_object->access_token;
        } else {
            // Handle the case where the access token is not present in the response
            return null;
        }
    }

    public function stkPush()
    {
        $user = $request->user;
            $amount = $request->amount;
            $phone =  $request->phone;
            $formatedPhone = substr($phone, 1);//726582228
            $code = "254";
            $phoneNumber = $code.$formatedPhone;//254726582228


        $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $curl_post_data = [
            'BusinessShortCode' => 174379,
            'Password' => $this->lipaNaMpesaPassword(),
            'Timestamp' => Carbon::now()->format('YmdHis'),
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phoneNumber,
            'PartyB' => 174379,
            'PhoneNumber' => $phoneNumber,
            // 'CallBackURL' => 'https://9002-102-212-30-22.ngrok-free.app/api/stk/push/callback/url',
            'CallBackURL' => 'https://mpesatesting.free.nf/daraja/callback.php',
            'AccountReference' => "SupaaDuka OnlineShopping",
            'TransactionDesc' => "lipa Na M-PESA Online Shopp"
        ];

        $data_string = json_encode($curl_post_data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $this->newAccessToken()));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);
        curl_close($curl);

        return redirect('/confirm'); 
    }

    public function MpesaRes(Request $request)
     {
        error_log("Mpesa method was called ");
        $response = json_decode($request->getContent());

        $trn = new MpesaTransaction;
        $trn->response = json_encode($response);
        $trn->save();
     }

     public function confirm()
     {
        return view('confirm');
     }
}
