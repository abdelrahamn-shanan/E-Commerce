<?php

namespace App\Http\Controllers\Site;

use App\Events\NewOrder;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class PaymentController extends Controller
{
   
   // private $apiURL;
    private $request_client;
    private $token;

    public function __construct(Client $request_client)
    {
        $this->request_client = $request_client;
        $this->apiURL = env('MYFATOORAHBASEURL');
        $this->token = env('MYFATOORAHTOKEN');
    }

    public function getPayments($amount)
    {
        return view('front.cart.payments',compact('amount'));

    }

    public function fatoorah(){
        
    }

   

    /**
     * @param Request $request
     */
    public function processPayment(Request $request)
    {
       
        $apiURL='https://apitest.myfatoorah.com';
        $apiKey='rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL';
        
        $error = '';

        //best practice as we do sperate validation on request form file
        $validator = Validator::make($request->all(), [
            'ccNum' => 'required',
            'ccExp' => 'required',
            'ccCvv' => 'required|numeric',
            'amount' => 'required|numeric|min:100',
        ]);

        if ($validator->fails()) {
            $error = 'Please check if you have filled in the form correctly. Minimum order amount is PHP 100.';
        }


        /* ------------------------ Call InitiatePayment Endpoint ------------------- */
//Fill POST fields array
$ipPostFields = ['InvoiceAmount' =>100, 'CurrencyIso' => 'KWD'];

//Call endpoint


//You can save $paymentMethods information in database to be used later
 $paymentMethodId = 20;


//Fill POST fields array
$postFields = [
    'paymentMethodId' => $paymentMethodId,
    'InvoiceValue'    => $request->amount,
    'CallBackUrl'     => 'https://example.com/callback.php',
    'ErrorUrl'        => 'https://example.com/callback.php',
];

function initiatePayment($apiURL, $apiKey, $postFields) {

    $json = callAPI("$apiURL/v2/InitiatePayment", $apiKey, $postFields);
    return $json->Data->PaymentMethods;

  }

  function executePayment($apiURL, $apiKey, $postFields) {

    $json = callAPI("$apiURL/v2/ExecutePayment", $apiKey, $postFields);
    return $json->Data;
}

//------------------------------------------------------------------------------
/*
 * Direct Payment Endpoint Function 
 */

function directPayment($paymentURL, $apiKey, $postFields) {

    $json = callAPI($paymentURL, $apiKey, $postFields);
    return $json->Data;
}

//------------------------------------------------------------------------------
/*
 * Call API Endpoint Function
 */

function callAPI($endpointURL, $apiKey, $postFields = [], $requestType = 'POST') {

    $curl = curl_init($endpointURL);
    curl_setopt_array($curl, array(
        CURLOPT_CUSTOMREQUEST  => $requestType,
        CURLOPT_POSTFIELDS     => json_encode($postFields),
        CURLOPT_HTTPHEADER     => array("Authorization: Bearer $apiKey", 'Content-Type: application/json'),
        CURLOPT_RETURNTRANSFER => true,
    ));

    $response = curl_exec($curl);
    $json = json_decode((string)$response, true);

    $curlErr  = curl_error($curl);

    curl_close($curl);

    if ($curlErr) {
        //Curl is not working in your server
        die("Curl Error: $curlErr");
    }

    $error = handleError($response);
    if ($error) {
        die("Error: $error");
    }

    return json_decode($response);
    
        
}

//Call endpoint
$paymentMethods = initiatePayment($apiURL, $apiKey, $ipPostFields);
foreach ($paymentMethods as $pm) {
    if ($pm->PaymentMethodEn == 'Visa/Master Direct 3DS Flow' && $pm->IsDirectPayment) {
        $paymentMethodId = $pm->PaymentMethodId;
        break;
    }
}
$data = executePayment($apiURL, $apiKey, $postFields);

//You can save payment data in database as per your needs
$invoiceId  = $data->InvoiceId;
$paymentURL = $data->PaymentURL;


/* ------------------------ Call DirectPayment Endpoint --------------------- */
//Fill POST fields array
$cardInfo = [
    'PaymentType' => 'card',
    'Bypass3DS'   => false,
    'Card'        => [
        'Number'         =>$request->ccNum,
        'ExpiryMonth'    => '05',
        'ExpiryYear'     => '22',
        'SecurityCode'   => $request->ccCvv,
        'CardHolderName' => $request->name
    ]
];

//Call endpoint
 $directData = directPayment($paymentURL, $apiKey, $cardInfo);

//You can save payment data in database as per your needs

$paymentLink = $directData->PaymentURL;

//Redirect your customer to the OTP page to complete the payment process
//Display the payment link to your customer
//echo "Click on <a href='$paymentLink' target='_blank'>$paymentLink</a> to pay with payment ID: $paymentId, and invoice ID: $invoiceId.";
$PaymentId   = $directData->PaymentId;
$amount = $request->amount;
$PaymentMethodId = $request->PaymentMethodId;

try {
   
    DB::beginTransaction();
    // if success payment save order and send realtime notification to admin
    $order = $this->saveOrder($amount, $PaymentMethodId);  // your task is  . add products with options to order to preview on admin
    $this->saveTransaction($order, $PaymentId);
    DB::commit();

    //fire event on order complete success for realtime notification
    event(new NewOrder($order));

} catch (\Exception $ex) {
    DB::rollBack();
    return $ex;
}
   
  return redirect()->back()->with(['success'=> "تم الدفع بنجاح"]);

/*return [
    'payment_success' => true,
    'token' => $PaymentId,
   // 'data' => $json,
    'status' => 'succeeded',
];*/

    }



    private function saveOrder($amount, $PaymentMethodId)
    {
        return Order::create([
            'customer_id' => auth()->id(),
            'customer_phone' => auth()->user()->mobile,
            'customer_name' => auth()->user()->name,
            'total' => $amount,
            'locale' => 'en',
            'payment_method' => $PaymentMethodId,  // you can use enumeration here as we use before for best practices for constants.
            'status' => Order::PAID,
        ]);

    }

    private function saveTransaction(Order $order, $PaymentId)
    {
        Transaction::create([
            'order_id' => $order->id,
            'transaction_id' => $PaymentId,
            'payment_method' => $order->payment_method,
        ]);
    }


};