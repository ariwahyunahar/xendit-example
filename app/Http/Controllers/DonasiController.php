<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
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
    
    public static function aes_decrypt($plaintext, $time){
        $iv = $time."000000";
        $cipher = "AES-256-CBC";
        $secret = "mD3aaLIhX6CI4JckoIk8mMZJOE4Rab3y";
        $options = 0;
        $encryptedString = openssl_decrypt($plaintext, $cipher, $secret, $options, $iv);
        return $encryptedString;
    }
    
    public static function object_to_array($obj) {
        //only process if it's an object or array being passed to the function
        if(is_object($obj) || is_array($obj)) {
            $ret = (array) $obj;
            foreach($ret as &$item) {
                //recursively process EACH element regardless of type
                $item = self::object_to_array($item);
            }
            return $ret;
        }
        //otherwise (i.e. for scalar values) return without modification
        else {
            return $obj;
        }
    }
    
    public function index(){
        $time = time();
        $trx_date = date('YmdHis'); // Action/Transaction initiated time;Format  YYYYMMDDHHMMSS (Example: 20190114170628)
        $partnerTrxID = '20746_'.uniqid(); // NIM + uniqid
        $callback_succes = "https://kafegamaa.com/api/callback";
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
        $callback_succes = "https://kafegamaa.com/api/callback";
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
            $pars["respon_url"] = $response->data->url;
            Log::log($pars);
            die('<a href="'.$response->data->url.'">'.$response->data->url.'</a>');
        }else{
            dddd($response);
        }
    }
    
    
    public function cekdonasi($trx_id = ''){
        $timestamp = "1669102592";
        $ivHashCiphertext = "o9OJcmFjH6PVW1ROg89CTS1PfGdO9Zx0GVHa7OD3/5CIPlhcZ5wN2ICoGW59utPSeaZ1da8NbvoWII7oTcPUS1trNLSieKbp95EeMFtXLyTvE+PiyOKd147MEInqDxyaf/jbjVc1ZZ0U6XV+Rz/JL/oC+RJ2mFpTYHSCJ6L2BhGpljXUiUcdebvBYlI4A7Wz5Bs8b2blsoM+Bm2qWsu5Nl3QmkN2C3AhhyREget8BSmV6ajc4Qsiv1Lf/jwvIdZz/3fwIYMBoAer8mITpRfhMfXSEtE62yOvelFDuWnMzGKebmLej8TqIDSqE6pClYIjZw2Z+QKMkA+JolGsWzWLpVFZEBqh3EzvWCTRP0h8I/pgJToM+ASytTeSXUQ3IrsniVdhucB7g+floGFLT1xO3+BEGcnNT9VSzylhEXnG5eaCS1ATM/zlEScNhyfHtcG0t2P2TsQ3Qb157tl0JD5xTg==";
        dddd(self::aes_decrypt($ivHashCiphertext, $timestamp));
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
        
        $return = "error";
        $detik = 0;
        while (true){
            sleep(3);
            $rs_par = curl_exec($curl);
            $response = json_decode($rs_par);
            if(isset($response->status)){
                if($response->status == '00'){
                    $return = "success";
                    break;
                }
            }
            if($detik > 30) break;
    
            $detik = $detik+3;
        }
        
        curl_close($curl);
        die("$return");
    }
    
    public function cektrx(Request $request){
        $time = time();
        if ($request->isMethod('post')) {
            $data = DB::table("__logs")->where("id", $request->trx_id)->first();
            
            if($request->trx_jenis == 'inquiry'){
                $datalog = json_decode($data->log);
                $trx_id = $datalog->partnerTrxID;
    
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
    
                $return = 0;
                $rs_par = curl_exec($curl);
                $response = json_decode($rs_par);
                if(isset($response->status)){
                    if($response->status == '00'){
                        $return = 1;
                    }
                }
    
                curl_close($curl);
    
                if($return){
                    DB::table("__logs")->where("id", $request->trx_id)->update([
                        'status' => 'lunas'
                    ]);
                    return view('msg.ok')->with([
                        'response' => $response
                    ]);
                }else{
                    return view('msg.err')->with([
                        'response' => $response
                    ]);
                }
            }else{ // REFUND
                $datalog = json_decode($data->log);
                $pars = [
                    'trxDate' => date('YmdHis'),
                    'partnerTrxID' => $datalog->partnerTrxID,
                    'merchantID' => $datalog->merchantID,
                    'terminalID' => $datalog->terminalID,
                    'terminalName' => $datalog->terminalName,
                    'totalAmount' => $datalog->totalAmount,
                    'partnerApplink' => $datalog->partnerApplink,
                    'items' => self::object_to_array($datalog->items),
                    'refData' => []
                ];
    
                $par = json_encode($pars);
                $hasil = self::aes($par, $time);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://partner-dev.linkaja.com/applink/v1/refund',
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
    
                $return = 0;
                $rs_par = curl_exec($curl);
                $response = json_decode($rs_par);
                if(isset($response->status)){
                    if($response->status == '00'){
                        $return = 1;
                    }
                }
                curl_close($curl);
    
                if($return){
                    DB::table("__logs")->where("id", $request->trx_id)->update([
                        'status' => 'refunded'
                    ]);
                    return view('msg.ok')->with([
                        'response' => $response
                    ]);
                }else{
                    return view('msg.err')->with([
                        'response' => $response
                    ]);
                }
            }
        }else{
            $data = DB::table("__logs")->orderByDesc("created_at")->limit(20)->get();
            return view('cektrx')->with(['data' => $data]);
        }
    }
}

