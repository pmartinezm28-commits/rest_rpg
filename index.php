<?php 
    try{
        require_once("autoload.php");
        Autoload::register();

        $controlador = new ControladorPersonaje();

        $metodo = strtoupper($_SERVER['REQUEST_METHOD']);
        switch($metodo){        
            case 'GET':       
                $personajes = $controlador->listar();      
                print_r($personajes); 
                $json = json_encode($personajes);
                header('Content-Type: application/json; charset=utf-8');      
                echo $json;
                 
                break;   

            case 'POST':
                $id = $controlador->insertar();
                $respuesta = "http://localhost/rest_rpg/id=" . $id;
                header('HTTP Response code 201');
                header('Content-Type: text/html; charset=utf-8');
                echo $respuesta;
                break;   

            case 'DELETE':
                $controlador->borrar();    
                header('HTTP Response code 201');
                header('Content-Type: text/html; charset=utf-8');
                echo "Borrado realizado";
                break;  

            case 'PUT':
                $controlador->modificar();
                header('HTTP Response code 201');
                header('Content-Type: text/html; charset=utf-8');
                echo "Modificación realizada";
                break;          

            default:
                http_response_code(501);
                echo 'Metodo no impletado';
                break;    
        }
    }
    catch(Throwable $e){
        echo "hola";
        header("HTTP/2 500 Internal Server Error");
        echo "Error en controlador personaje en listar ". $e . " <br>";
        die();
    }