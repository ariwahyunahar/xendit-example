<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Xendit\Xendit;

class DonasiController extends Controller
{
    //
    public function index(){
        Xendit::setApiKey('xnd_development_t9B6zyt5VVtHgJkLBoHrmOx49M6oRzpiz2IZJ5BeDluGeGbO9jarnRktudwztt');
    
//        $getBalance = \Xendit\Balance::getBalance('CASH');
//        dd($getBalance);
    
        $params = [
            'reference_id' => 'test-reference-id',
            'currency' => 'IDR',
            'amount' => 1000,
            'checkout_method' => 'ONE_TIME_PAYMENT',
            'channel_code' => 'ID_SHOPEEPAY',
            'channel_properties' => [
                'success_redirect_url' => 'https://dashboard.xendit.co/register/1',
            ],
            'metadata' => [
                'branch_code' => 'tree_branch'
            ]
        ];
    
        $createEWalletCharge = \Xendit\EWallets::createEWalletCharge($params);
        dd($createEWalletCharge);
        
    }
    public function donasi(Request $request){
    
        $curl = curl_init();
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $end_point = 'https://api.xendit.co/balance';
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_USERPWD, "xnd_development_t9B6zyt5VVtHgJkLBoHrmOx49M6oRzpiz2IZJ5BeDluGeGbO9jarnRktudwztt:");
        curl_setopt($curl, CURLOPT_URL, $end_point);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $responseObject = json_decode($response, true);
        
        dd($responseObject);
    
    
    
        return view('welcome');
    }
    
    public function callback(Request $request){
        DB::table('__logs')->insert([
            'log' => json_encode($request->all()),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
