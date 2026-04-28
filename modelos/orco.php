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

        function insertar(){
            try{
                $sql = "INSERT INTO orco(id_personaje, id_orco, clan, fuerza_extra) VALUES (?,?,?,?)";
                $parametros = [$this->id_personaje, $this->id_orco, $this->clan, $this->fuerza_extra ];

                $this->bd->insertar($sql, $parametros);

            }catch(Throwable $e){
                header("HTTP/2 500 Server Error");
                echo "Error en orco en insertar ". $e . " <br>";
            }
        }

    }