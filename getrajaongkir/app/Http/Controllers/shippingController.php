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
            "ro_key"=>$this->RO_KEY,
            "ro_url"=>$this->RO_URL
        ];
    }
    function city(Request $request)
    {
        // $destination= $request->destination;
        // $courier= $request->courier;
        // $origin = 501; // id kota jogja

        // $params = "origin=$origin&destination=$destination&weight=500&courier=$courier";
        // dd($params);
        $data = $this->request_raja_ongkir("city?province=$request->province","GET","");
        return [
            "data"=>$data,
            "ro_key"=>$this->RO_KEY,
            "ro_url"=>$this->RO_URL
        ];
    }

    function costs(Request $request)
    {
        // $destination= $request->destination;
        // $courier= $request->courier;
        // $origin = 501; // id kota jogja

        // $params = "origin=$origin&destination=$destination&weight=500&courier=$courier";
        // dd($params);
        // $data = $this->request_raja_ongkir("city?province=$request->province","GET","");
        // return [
        //     "data"=>$data,
        //     "ro_key"=>$this->RO_KEY,
        //     "ro_url"=>$this->RO_URL
        // ];
        $result = [];
        $destination = $request->destination;
        $origin = $request->origin;
        $couriers = $request->courier ?? ['jne','pos','tiki'];

        foreach ($couriers as $key => $value) {
            $param = "origin=$origin&destination=$destination&courier=$value&weight=500";
            $response = $this->request_raja_ongkir("cost","POST",$param);
            if (count($response)) {
                $response = $response[0];
                foreach ($response->costs as $key => $item) {
                    $cost = $item->cost[0];
                    $etdRaw =  $value == 'pos' ? str_replace(" HARI","",$cost->etd) : $cost->etd;
                    $arrMinMax =explode("-",$etdRaw);
                    $text="";
                    if(count($arrMinMax)==1){
                        $text=$this->fulldate(date("Y-m-d",strtotime("+ $arrMinMax[0] day")));
                    }else if (count($arrMinMax)==2){
                        $text=$this->fulldate(date("Y-m-d",strtotime("+ $arrMinMax[0] day")))." - ".$this->fulldate(date("Y-m-d",strtotime("+ $arrMinMax[1] day")));
                    }
                    $cost->label = strtoupper( $response->costs[$key]->service);
                    $cost->etd_text = ($etdRaw=="")?"":$text;
                    $cost->etd = $etdRaw;

                    $response->costs[$key]->cost = $cost;
                }
                
                $result[]=$response;
            }
        }

        return $result;
    }

    function index(Request $request)
    {
        return view('welcome',$this->provinces($request));
    }

    function fulldate($parm)
    {
        if ($parm == '0000-00-00 00:00:00') {
            return "";
        }
        $array_bulan = array(1 => "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");
        $dataBulan = date('n', strtotime($parm));

        $array_hari = array(1 => "Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min");
        $dataHari = date('N', strtotime($parm));

        return date('d', strtotime($parm)) . " " . $array_bulan[$dataBulan] . " " . date('Y', strtotime($parm));
    }
}
