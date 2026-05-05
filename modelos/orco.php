<?php

    class orco{
        private $id_personaje;
        private $id_orco;
        private $clan;
        private $fuerza_extra;
        private $bd;
        

        public function __construct($id_orco, $id_personaje, $clan, $fuerza_extra) {
            $this->id_personaje = $id_personaje;
            $this->id_orco = $id_orco;
            $this->clan = $clan;
            $this->fuerza_extra = $fuerza_extra;
            $this->bd = new BD;
        }

        public function insertar(){
            try{
                $sql = "INSERT INTO orco(id_personaje, id_orco, clan, fuerza_extra) VALUES (?,?,?,?)";
                $parametros = [$this->id_personaje, $this->id_orco, $this->clan, $this->fuerza_extra ];

                $this->bd->insertar($sql, $parametros);

            }catch(Throwable $e){
                header("HTTP/2 500 Server Error");
                echo "Error en orco en insertar ". $e . " <br>";
            }
        }
        
        public static function modificar($id, $clan, $fuerza_extra) {
            $bd = new BD();
            try{
                $bd->getConexion()->beginTransaction();
                 $sql = "UPDATE orco
                        SET clan = ?, fuerza_extra = ?
                        WHERE id_personaje = $id";
                        
                $parametros = [$clan, $fuerza_extra];
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