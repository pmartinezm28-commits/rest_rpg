<?php

    class ControladorPersonaje {
        
        public function listar(){
            try{
                $personajes = Personaje::listar();
                return $personajes;
            }
            catch(Throwable $e){
                echo "hola";
                header("HTTP/2 500 Internal Server Error");
                echo "Error en controlador personaje en listar ". $e . " <br>";
                die();
            }
        }

        public function borrar(){
            try{
                $body = file_get_contents('php://input');
                $id = json_decode($body, true);
                Personaje::borrar($id);
            }
            catch(Throwable $e){
                header("HTTP/2 500 Internal Server Error");
                echo "Error en controlador personaje en borrar ". $e . " <br>";
                die();
            }

        }

        // Devuelve el id del personaje
        public function insertar(){
            $body = file_get_contents('php://input');
            $array_personaje = json_decode($body, true);

            // Nombre
            if(!isset($array_personaje["nombre"])){
                header("HTTP/2 400 Bad request");
                echo "Falta el parámetro nombre";    
                die();
            }
            $nombre = $array_personaje["nombre"];

            if (strlen(htmlspecialchars($nombre)) < 3) {
                header("HTTP/2 400 Bad request");
                echo 'Parámetro nombre longitud insuficiente';
                die();
            }

            // Nivel
            if(!isset($array_personaje["nivel"])){
                header("HTTP/2 400 Bad request");
                echo "Falta el parámetro nivel";    
                die();
            }
            
            $nivel = $array_personaje["nivel"];

            // Es un número
            if (!is_numeric(htmlspecialchars($nivel))) {
                header("HTTP/2 400 Bad request");
                echo 'Parámetro nivel debe ser un número';
                die();
            }

            //Puntos de vida
            if(!isset($array_personaje["puntos_vida"])){
                header("HTTP/2 400 Bad request");
                echo "Falta el parámetro nivel";    
                die();
            }
            $puntos_vida = $array_personaje["puntos_vida"];

            if (!is_numeric(htmlspecialchars($puntos_vida))) {
                header("HTTP/2 400 Bad request");
                echo 'Parámetro puntos de vida debe ser numérico';
                die();
            }
            
            $personaje = new Personaje($nombre, $nivel, $puntos_vida);
            $bd = null;
            try{
                // Iniciamos transacción
                $bd = new BD();
                $bd->getConexion()->beginTransaction();
                $id = $personaje->insertar();
                //Insertamos humanos
                if(isset($array_personaje['reino'])){
                    $array_humano = $this->validarHumano($array_personaje);
                    $humano = new Humano($id, $id, $array_humano["reino"], $array_humano["profesion"]);
                    $humano->insertar();
                }

                // inserto elfo
                if(isset($array_personaje['tipo'])){
                    $array_elfo = $this->validarElfo($array_personaje);
                    $elfo = new Elfo($id, $id, $array_elfo["tipo"], $array_elfo["habilidad_especial"]);
                    $elfo->insertar();  
                }
                // inserto orco
                if(isset($array_personaje['clan'])){
                    $array_orco = $this->validarOrco($array_personaje);
                    $orco = new Orco($id, $id, $array_orco["clan"], $array_orco["fuerza_extra"]);
                    $orco->insertar();  
                }

                
                $bd->getConexion()->commit();
                return $id;
            }
            catch(Throwable $e){
                header("HTTP/2 500 Internal Server Error");
                echo "Error en controlador_personaje en insertar";
                $bd->getConexion()->rollBack();
            }        
        }

        public function modificar() {
            $body = file_get_contents('php://input');
            $array_personaje = json_decode($body, true);
            print_r($array_personaje);

            // ID
            if(!isset($array_personaje["id"])){
                header("HTTP/2 400 Bad request");
                echo "Falta el parámetro id";    
                die();
            }
            $id = $array_personaje["id"];
            // Nombre
            if(!isset($array_personaje["nombre"])){
                header("HTTP/2 400 Bad request");
                echo "Falta el parámetro nombre";    
                die();
            }
            $nombre = $array_personaje["nombre"];

            if (strlen(htmlspecialchars($nombre)) < 3) {
                header("HTTP/2 400 Bad request");
                echo 'Parámetro nombre longitud insuficiente';
                die();
            }

            // Nivel
            if(!isset($array_personaje["nivel"])){
                header("HTTP/2 400 Bad request");
                echo "Falta el parámetro nivel";    
                die();
            }
            
            $nivel = $array_personaje["nivel"];

            // Es un número
            if (!is_numeric(htmlspecialchars($nivel))) {
                header("HTTP/2 400 Bad request");
                echo 'Parámetro nivel debe ser un número';
                die();
            }

            //Puntos de vida
            if(!isset($array_personaje["puntos_vida"])){
                header("HTTP/2 400 Bad request");
                echo "Falta el parámetro nivel";    
                die();
            }
            $puntos_vida = $array_personaje["puntos_vida"];

            if (!is_numeric(htmlspecialchars($puntos_vida))) {
                header("HTTP/2 400 Bad request");
                echo 'Parámetro puntos de vida debe ser numérico';
                die();
            }
            
            $personaje = new Personaje($nombre, $nivel, $puntos_vida);
            $bd = null;
            try{
                // Iniciamos transacción
                $bd = new BD();
                $bd->getConexion()->beginTransaction();
                $personaje->modificar();
                //Insertamos humanos
                if(isset($array_personaje['reino'])){
                    $array_humano = $this->validarHumano($array_personaje);
                    $humano = new Humano($id, $id, $array_humano["reino"], $array_humano["profesion"]);
                    $humano->modificar();
                }

                // inserto elfo
                if(isset($array_personaje['tipo'])){
                    $array_elfo = $this->validarElfo($array_personaje);
                    $elfo = new Elfo($id, $id, $array_elfo["tipo"], $array_elfo["habilidad_especial"]);
                    $elfo->modificar();  
                }
                // inserto orco
                if(isset($array_personaje['clan'])){
                    $array_orco = $this->validarOrco($array_personaje);
                    $orco = new Orco($id, $id, $array_orco["clan"], $array_orco["fuerza_extra"]);
                    $orco->modificar();  
                }

                
                $bd->getConexion()->commit();
                
            }
            catch(Throwable $e){
                header("HTTP/2 500 Internal Server Error");
                echo "Error en controlador_personaje en insertar";
                $bd->getConexion()->rollBack();
            }
        }

        public function validarHumano($array_personaje) {
            
            if (isset($array_personaje['reino']) && isset($array_personaje['profesion'])) {
                
                $reino = $array_personaje["reino"];
                // Reino
                if (strlen(htmlspecialchars($reino)) < 3) {
                    header("HTTP/2 400 Bad request");
                    echo 'Reino introducido muy corto';
                    die();
                }
                
                $profesion = $array_personaje['profesion'];
                
                // Profesión
                if (strlen(htmlspecialchars($profesion)) < 3) {
                    header("HTTP/2 400 Bad request");
                    echo 'Profesión introducido muy corta';
                    die();
                }
                
                $array_humano = ["reino"=>$reino, "profesion"=>$profesion];
                return $array_humano;
            }
        }

        public function validarElfo($array_personaje){
            if (isset($array_personaje['tipo']) && isset($array_personaje['habilidad_especial'])) {
            
                $tipo = $array_personaje['tipo'];
                
                if (strlen(htmlspecialchars($tipo)) < 3) {
                    header("HTTP/2 400 Bad request");
                    echo 'Parametro Tipo introducido muy corto';
                    die();
                }
                
                $habilidad_especial = $array_personaje['habilidad_especial'];
                
                if (strlen(htmlspecialchars($habilidad_especial)) < 3) {
                    header("HTTP/2 400 Bad request");
                    echo 'Parametro Habilidad especial introducido muy corta';
                    die();
                }

                $array_elfo = ["tipo"=>$tipo, "habilidad_especial"=>$habilidad_especial];

                return $array_elfo;
            }
        }

        public function validarOrco($array_personaje) {
            if (isset($array_personaje['clan']) && isset($array_personaje['fuerza_extra'])) {
                
                $clan = $array_personaje['clan'];

                if (strlen(htmlspecialchars($clan)) < 3) {
                    header("HTTP/2 400 Bad request");
                    echo 'Parametro Clan introducido muy corto';
                    die();
                }
                
                $fuerza_extra = $array_personaje['fuerza_extra'];

                if (!is_numeric(htmlspecialchars($fuerza_extra))) {
                    header("HTTP/2 400 Bad request");
                    echo 'Parametro Fuerza extra introducido muy corto';
                    die();
                }

                $array_orco = ["clan"=>$clan, "fuerza_extra"=>$fuerza_extra];

                return $array_orco;
            }
        }
    }
