<?php
    // Código de: 
    // http://programandolo.blogspot.mx/2013/08/ejemplo-php-de-servicio-restful-parte-1.html
    class Rest {

        public $tipo = "application/json";
        public $datosPeticion = array();
        private $_codEstado = 200;

        public function __construct() {
            $this->tratarEntrada();
        }

        public function mostrarRespuesta($data, $estado) {
            $this->_codEstado = ($estado) ? $estado : 200;//si no se envía $estado por defecto será 200
            $this->setCabecera();
            echo $data."\n";
            exit;
        }

        private function setCabecera() {  
            header("HTTP/1.1 " . $this->_codEstado . " " . $this->getCodEstado());  
            header("Content-Type:" . $this->tipo . ';charset=utf-8');  
        }

        private function limpiarEntrada($data) {
            #echo "Estoy en limpiarEntrada(), recibi lo siguiente:\n";
            #print_r($data);
            $entrada = array();  
            if (is_array($data)) {  
                foreach ($data as $key => $value) {  
                    $entrada[$key] = $this->limpiarEntrada($value);
                }
            } else {  
                if (get_magic_quotes_gpc()) {  
                    //Quitamos las barras de un string con comillas escapadas  
                    //Aunque actualmente se desaconseja su uso, muchos servidores tienen activada la extensión magic_quotes_gpc.   
                    //Cuando esta extensión está activada, PHP añade automáticamente caracteres de escape (\) delante de las comillas que se escriban en un campo de formulario.   
                    $data = trim(stripslashes($data));
                }
                //eliminamos etiquetas html y php  
                $data = strip_tags($data);  
                //Conviertimos todos los caracteres aplicables a entidades HTML  
                $data = htmlentities($data);  
                $entrada = trim($data);  
            }
            return $entrada;  
        }

        private function tratarEntrada() {  
            #echo "URL: ".$_SERVER['REQUEST_URI']."\n\n";
            $metodo = $_SERVER['REQUEST_METHOD'];  
            #echo "Metodo: ".$metodo."\n\n";
            switch ($metodo) {  
                case "GET":  
                    $this->datosPeticion = $this->limpiarEntrada($_GET);
                    break;
                // sin definicion los siguientes metodos
                case "POST":  
                    $this->datosPeticion = $this->limpiarEntrada($_POST);  
                    break;  
                case "DELETE"://"falling though". Se ejecutará el case siguiente  
                case "PUT":  
                   //php no tiene un método propiamente dicho para leer una petición PUT o DELETE por lo que se usa un "truco":  
                   //leer el stream de entrada file_get_contents("php://input") que transfiere un fichero a una cadena.  
                   //Con ello obtenemos una cadena de pares clave valor de variables (variable1=dato1&variable2=data2...)
                   //que evidentemente tendremos que transformarla a un array asociativo.  
                   //Con parse_str meteremos la cadena en un array donde cada par de elementos es un componente del array.  
                    parse_str(file_get_contents("php://input"), $this->datosPeticion);
                    $this->datosPeticion = $this->limpiarEntrada($this->datosPeticion);
                    break;
                default:
                    $this->response('', 404);
                    break;
            }
        }

        private function getCodEstado() {  
            // Status Code definition
            // http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
            $estado = array(  
                200 => 'OK',  
                201 => 'Created',  
                202 => 'Accepted',  
                204 => 'No Content',  
                301 => 'Moved Permanently',  
                302 => 'Found',  
                303 => 'See Other',  
                304 => 'Not Modified',  
                400 => 'Bad Request',  
                401 => 'Unauthorized',  
                403 => 'Forbidden',  
                404 => 'Not Found',  
                405 => 'Method Not Allowed',  
                500 => 'Internal Server Error');  
            $respuesta = ($estado[$this->_codEstado]) ? $estado[$this->_codEstado] : $estado[500];  
            return $respuesta;  
        }  
    }  
    ?>