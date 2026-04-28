<?php

    class Elfo{
        private $id_personaje;
        private $id_elfo;
        private $tipo;
        private $habilidad_especial;
        private $bd;
        

        public function __construct($id_elfo, $id_personaje, $tipo, $habilidad_especial) {
            $this->id_personaje = $id_personaje;
            $this->id_elfo = $id_elfo;
            $this->tipo = $tipo;
            $this->habilidad_especial = $habilidad_especial;
            $this->bd = new BD;
        }

        function insertar(){
            try{
                $sql = "INSERT INTO Elfo(id_personaje, id_elfo, tipo, habilidad_especial) VALUES (?,?,?,?)";
                $parametros = [$this->id_personaje, $this->id_elfo, $this->tipo, $this->habilidad_especial ];

                $this->bd->insertar($sql, $parametros);

            }catch(Throwable $e){
                header("HTTP/2 500 Server Error");
                echo "Error en elfo en insertar ". $e . " <br>";
            }
        }

    }