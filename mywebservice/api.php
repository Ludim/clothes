<?php
    // Código de: 
    // http://programandolo.blogspot.mx/2013/08/ejemplo-php-de-servicio-restful-parte-1.html
    // incluye unas ligera modificacion para hacerlo acorde a lo que se necesita
    require_once "Rest.php";
    require_once "../Clothes.php"; 

    class Api extends Rest {
        const INDEX_NAME = "WomenClothes";
        private $index = NULL;

        private $_metodo;  
        private $_argumentos;  
        
        public function __construct() {  
            parent::__construct();
            $this->setIndex();
        }  
            
        public function setIndex()
        {
            $this->index = new Clothes(self::INDEX_NAME);
        }

        private function devolverError($id) {  
         $errores = array(  
           array('estado' => "error", "msg" => "petición no encontrada"),  
           array('estado' => "error", "msg" => "petición no aceptada"),  
           array('estado' => "error", "msg" => "petición sin contenido"),  
           array('estado' => "error", "msg" => "no existe item")
         );  
         return $errores[$id];  
        }

        public function procesarLLamada() {  
            #echo "\nprocesarLLamada()\n\n";
            #print_r($_REQUEST);
            if (isset($_REQUEST['url'])) {  
                //si por ejemplo pasamos explode('/','////controller///method////args///') el resultado es un array con elem vacios;
                //Array ( [0] => [1] => [2] => [3] => [4] => controller [5] => [6] => [7] => method [8] => [9] => [10] => [11] => args [12] => [13] => [14] => )
                $url = explode('/', trim($_REQUEST['url']));
                //con array_filter() filtramos elementos de un array pasando función callback, que es opcional.
                //si no le pasamos función callback, los elementos false o vacios del array serán borrados 
                //por lo tanto la entre la anterior función (explode) y esta eliminamos los '/' sobrantes de la URL
                $url = array_filter($url);
                $this->_metodo = strtolower(array_shift($url));
                #echo "metodo   ". $this->_metodo."\n";
                $this->_argumentos = $url;//strtolower(array_pop($url));;
                //echo "Consulta ".$this->_argumentos."\n\n";
                $func = $this->_metodo;
                if ((int) method_exists($this, $func) > 0) {
                    if (count($this->_argumentos) > 0) {
                        call_user_func_array(array($this, $this->_metodo), $this->_argumentos);
                    } else {//si no lo llamamos sin argumentos, al metodo del controlador
                        call_user_func(array($this, $this->_metodo));
                    }
                } else  {
                    $this->mostrarRespuesta($this->convertirJson($this->devolverError(0)), 404);
                }
            }  
            $this->mostrarRespuesta($this->convertirJson($this->devolverError(0)), 404);  
        }

        private function convertirJson($data) {  
            return json_encode(array("item"=>$data));
            #return json_encode(array("items" =>$data), JSON_PRETTY_PRINT); 
        }

        private function getitems(){   
            #echo "\n\ngetitems()\n\n";
            // Verifica haber recibido un request GET
            if($_SERVER['REQUEST_METHOD'] != "GET"){
                $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
            }
            if (isset($this->datosPeticion['clothes'])) {
                $clothes = $this->datosPeticion['clothes'];
                if(!empty($clothes)) {
                    $infoClothes = $this->index->getInformationClothes($clothes, 10);
                    #print_r($infoClothes);
                    if($infoClothes != "Please, try with other option") {
                        $this->mostrarRespuesta($this->convertirJson($infoClothes), 200);
                    } else {
                        $this->mostrarRespuesta($this->convertirJson($this->devolverError(3)), 200);  
                    }
                }
            }
        }
    }
    $api = new Api();
    $api->procesarLLamada();  

/*
// process client request (VIA URL)
    header("Content-Type:application/json");
    include_once 'functions.php';
    #echo $_SERVER['REQUEST_URI'];
    if(!empty($_GET['clothes'])) {
        $name = $_GET["clothes"];
        $price = get_price($name);

        if(empty($price)) {
            // book not found
            deliver_response(200,"book not found", NULL);
        } else {
            // respond book price
            deliver_response(200,"book found", $price);
        }
    } else {
        // throw invalid request
        deliver_response(400,"Invalid Request", NULL);
    }

    function deliver_response($status,$status_message,$data)
    {
        header("HTTP/1.1 $status $status_message");

        $response['status'] = $status;
        $response['status_message'] = $status_message;
        $response['data'] = $data;

        $json_response = json_encode($response);
        echo $json_response;
    }

    error_reporting(E_ALL);
    ini_set("display_errors","On");

*/