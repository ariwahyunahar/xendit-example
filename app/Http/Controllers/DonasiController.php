<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Xendit\Xendit;

class DonasiController extends Controller
{
    //
    /* // pembayaran/donasi/payment
     * http://127.0.0.1:8000
     * // cek status
     * http://127.0.0.1:8000/cekdonasi/<$partnerTrxID>
    */
    
    public static function aes($plaintext, $time){
        $iv = $time."000000";
        $cipher = "AES-256-CBC";
        $secret = "mD3aaLIhX6CI4JckoIk8mMZJOE4Rab3y";
        $options = 0;
//        $iv = str_repeat ("0",openssl_cipher_iv_Length($cipher));
//        $iv = "0000000000000000";
        $encryptedString = openssl_encrypt($plaintext, $cipher, $secret, $options, $iv);
        return $encryptedString;
    }
    
    public function index(){
        $time = time();
        $trx_date = date('YmdHis'); // Action/Transaction initiated time;Format  YYYYMMDDHHMMSS (Example: 20190114170628)
        $partnerTrxID = '20746_'.uniqid(); // NIM + uniqid
        $callback_succes = "https://xendit.sipgaji.com/api/callback";
        $merchant_id = "kafegama_app"; // ID Merchant Kafegama
        $terminalID = "kafegama"; // Misal: App Kafegama, yg lain
        $terminalName = "Kafegama"; // Misal: App Kafegama, yg lain
        $totalAmount = "1000"; // Nominal yang dikirim ke LinkAJa Kafegama
        $items = [
            [
                "id" => "1",
                "name" => "Bantuan Korban Bencana",
                "unitPrice" => "1000",
                "qty" => "1",
            ]
        ];
        
        $pars = [
            'trxDate' => $trx_date,
            'partnerTrxID' => $partnerTrxID,
            'merchantID' => $merchant_id,
            'terminalID' => $terminalID,
            'terminalName' => $terminalName,
            'totalAmount' => $totalAmount,
            'partnerApplink' => $callback_succes,
            'items' => $items,
            'refData' => []
        ];
        
        /*
        $par = '{
            "trxDate" : "'.$trx_date.'",
            "partnerTrxID" : "'.$partnerTrxID.'",
            "merchantID" : "kafegama_app",
            "terminalID" : "kafegama",
            "terminalName" : "Kafegama",
            "totalAmount" : "1000",
            "partnerApplink": "'.$callback_succes.'",
            "items": [
                {
                    "id": "1",
                    "name": "Bantuan Korban Bencana",
                    "unitPrice" : "1000",
                    "qty": "1"
                }
            ] ,
            "refData": []
        }';
        */
        $par = json_encode($pars);
        $hasil = self::aes($par, $time);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://partner-dev.linkaja.com/applink/v1/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $hasil,
            CURLOPT_HTTPHEADER => array(
                'timestamp: '.$time.'000000',
                'User-Agent: Web',
                'Authorization: Basic '.base64_encode("kafegama:l1nk4j4#k@f3gaMa"),
                'Content-Type: text/plain'
            ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $response = json_decode($response);
        if(isset($response->data->url)){
            die('<a href="'.$response->data->url.'">'.$response->data->url.'</a>');
        }else{
            dddd($response);
        }
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
    
    public function do_donasi(Request $request){
        $time = time();
        $trx_date = date('YmdHis'); // Action/Transaction initiated time;Format  YYYYMMDDHHMMSS (Example: 20190114170628)
        $partnerTrxID = '20746_'.uniqid(); // NIM + uniqid
        $callback_succes = "https://xendit.sipgaji.com/api/callback";
        $merchant_id = "kafegama_app"; // ID Merchant Kafegama
        $terminalID = "kafegama"; // Misal: App Kafegama, yg lain
        $terminalName = "Kafegama"; // Misal: App Kafegama, yg lain
        $totalAmount = "1000"; // Nominal yang dikirim ke LinkAJa Kafegama
        $items = [
            [
                "id" => "1",
                "name" => "Bantuan Korban Bencana",
                "unitPrice" => "1000",
                "qty" => "1",
            ]
        ];
    
        $pars = [
            'trxDate' => $trx_date,
            'partnerTrxID' => $partnerTrxID,
            'merchantID' => $merchant_id,
            'terminalID' => $terminalID,
            'terminalName' => $terminalName,
            'totalAmount' => $totalAmount,
            'partnerApplink' => $callback_succes,
            'items' => $items,
            'refData' => []
        ];
    
        /*
        $par = '{
            "trxDate" : "'.$trx_date.'",
            "partnerTrxID" : "'.$partnerTrxID.'",
            "merchantID" : "kafegama_app",
            "terminalID" : "kafegama",
            "terminalName" : "Kafegama",
            "totalAmount" : "1000",
            "partnerApplink": "'.$callback_succes.'",
            "items": [
                {
                    "id": "1",
                    "name": "Bantuan Korban Bencana",
                    "unitPrice" : "1000",
                    "qty": "1"
                }
            ] ,
            "refData": []
        }';
        */
        $par = json_encode($pars);
        $hasil = self::aes($par, $time);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://partner-dev.linkaja.com/applink/v1/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $hasil,
            CURLOPT_HTTPHEADER => array(
                'timestamp: '.$time.'000000',
                'User-Agent: Web',
                'Authorization: Basic '.base64_encode("kafegama:l1nk4j4#k@f3gaMa"), // Username & Password Kafegama
                'Content-Type: text/plain'
            ),
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
        $response = json_decode($response);
        if(isset($response->data->url)){
            echo $partnerTrxID."<br>";
            echo $time."<br>";
            
            die('<a href="'.$response->data->url.'">'.$response->data->url.'</a>');
        }else{
            dddd($response);
        }
    }
    
    
    public function cekdonasi($trx_id = ''){
        $time = time();
        $partnerTrxID = $trx_id; // sesuai transaksi sebelumnya
        $merchant_id = "kafegama_app"; // ID Merchant Kafegama
        $terminalID = "kafegama"; // Misal: Kafegama, app yg lain
        
        $pars = [
            'partnerTrxID' => $partnerTrxID,
            'merchantID' => $merchant_id,
            'terminalID' => $terminalID,
        ];
        
        $par = json_encode($pars);
        $hasil = self::aes($par, $time);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://partner-dev.linkaja.com/applink/v1/inquiry',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $hasil,
            CURLOPT_HTTPHEADER => array(
                'timestamp: '.$time.'000000',
                'User-Agent: Web',
                'Authorization: Basic '.base64_encode("kafegama:l1nk4j4#k@f3gaMa"), // Username & Password Kafegama
                'Content-Type: text/plain'
            ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $response = json_decode($response);
        dddd($response);
    }
}

