<?php

    class Humano{
        private $id_personaje;
        private $id_humano;
        private $reino;
        private $profesion;
        private $bd;

        public function __construct($id_personaje, $id_humano, $reino, $profesion) {
            $this->id_humano = $id_humano;
            $this->id_personaje = $id_personaje;
            $this->reino = $reino;
            $this->profesion = $profesion;
            $this->bd = new BD;
        }

        public function insertar(){
            try{
                $sql = "INSERT INTO humano (id_personaje, id_humano, reino, profesion) VALUES (?,?,?,?)";
                $parametros = [$this->id_humano, $this->id_personaje, $this->reino, $this->profesion];

                $this->bd->insertar($sql, $parametros);    
            }
            catch(Throwable $e){
                header("HTTP/2 500 Server Error");
                echo "Error en humano en insertar ". $e . " <br>";
            }
        }

        public static function modificar($id, $reino, $profesion){
            $bd = new BD();
            try{
                $bd->getConexion()->beginTransaction();
                
                $sql = "UPDATE humano
                        SET reino = ?, profesion = ?
                        WHERE id_personaje = $id";

                $parametros = [$reino, $profesion];
                $bd->modificar($sql, $parametros); 
                
                $bd->getConexion()->commit();       
            } catch(Throwable $e){
                header("HTTP/2 500 Server Error");
                echo "Error en humano en insertar ". $e . " <br>";
                $bd->getConexion()->rollBack();
                die();
            }
        }
    }