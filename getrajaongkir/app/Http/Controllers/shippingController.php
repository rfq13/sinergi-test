<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class shippingController extends Controller
{
    private $RO_URL = "https://api.rajaongkir.com/starter";
    private $RO_KEY = "65b9de184d3cfa4c216155a8aae6f22d";

    function request_raja_ongkir($url,$tipe = 'GET',$param)
    {
        $url = "$this->RO_URL/$url";
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $tipe,
        CURLOPT_POSTFIELDS => $param,
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key: ".$this->RO_KEY
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {

          return "cURL Error #:" . $err;
        } else {
           $_respose = json_decode($response);
           if ($_respose->rajaongkir->status->code == 200)
           {
            return $_respose->rajaongkir->results;
          }else{
             return [];
          }


        }

    }

    function provinces(Request $request)
    {
        // $destination= $request->destination;
        // $courier= $request->courier;
        // $origin = 501; // id kota jogja

        // $params = "origin=$origin&destination=$destination&weight=500&courier=$courier";
        $params = "";
        // dd($params);
        $data = $this->request_raja_ongkir("province","GET",$params);
        return [
            "data"=>$data,
            "key"=>$this->RO_KEY,
            "ro_url"=>$this->RO_URL
        ];
    }

    function index(Request $request)
    {
        return view('welcome',$this->provinces($request));
    }
}
