<?php

class Test {
     public $baseUrl = "";
     private $showFizzbuzz = 0;  
     
     function __construct()
     {
          $this->baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
     }

     function modifiedFizzBuzz($a) {
          $result = [];
          if ($a) {
               for ($i=1; $i <= $a; $i++) {
                    if ($this->showFizzbuzz == 5) break;
                    if ($i % 3 == 0 && $i % 5 == 0) {
                         array_push($result,[
                              "index"=>$i,
                              "result"=>"Pasar 20 Belanja Pangan"
                         ]);
                         $this->showFizzbuzz++;
                    }elseif ($i % 3 == 0) {
                         array_push($result,[
                              "index"=>$i,
                              "result"=> $this->showFizzbuzz > 1 ? "Belanja Pangan" : "Pasar 20"
                         ]);
                    }elseif ($i % 5 == 0) {
                         array_push($result,[
                              "index"=>$i,
                              "result"=>$this->showFizzbuzz > 1 ? "Pasar 20" : "Belanja Pangan"
                         ]);
                    }else {   
                         array_push($result,[
                              "index"=>$i,
                              "result"=>""
                         ]);
                    }
               }
          }

          return $result;
     }
     
     public function view()
     {
          $angka = $_POST['angka'] ?? false;
          $results = $this->modifiedFizzBuzz($angka);
          
          include('./view/index.php');
     }
}


$test = new Test();
$test->view();


