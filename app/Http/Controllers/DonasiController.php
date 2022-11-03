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
            'reference_id' => uniqid(),
            'currency' => 'IDR',
            'amount' => 1000,
            'checkout_method' => 'ONE_TIME_PAYMENT',
            'channel_code' => 'ID_LINKAJA',
            'channel_properties' => [
                'success_redirect_url' => 'https://xendit.sipgaji.com/donasi/ok',
            ],
            'metadata' => [
                'branch_code' => 'tree_branch'
            ]
        ];
    
        $createEWalletCharge = \Xendit\EWallets::createEWalletCharge($params);
        dd($createEWalletCharge);
        
    }
    
    public function callback(Request $request){
        DB::table('__logs')->insert([
            'log' => json_encode($request->all()),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function donasiok(){
         return view('donasi.ok');
    }
}
