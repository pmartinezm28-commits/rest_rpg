<?php 
    class bd{
        private $conexion;

        public function __construct() {
            try {
                //$config = require('config.php');      

                $this->conexion =  new PDO("sqlite:./bd/juego_rol.sqlite");
                $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conexion->exec("PRAGMA foreign_keys = ON");
            } catch (PDOException $excepcion) {
                header ('HTTP/2 500 Internal Server Error');
                echo "Error en bd construct " .$excepcion ." <br>";
            }
        }

        public function getConexion(){
            return $this->conexion;
        }

        // Realiza la insercion de una sql en la bd
        public function insertar($sql, $parametros){

            $sentencia = $this->conexion->prepare($sql);

            $sentencia -> execute($parametros);

            return $this->conexion->lastInsertId();
        }

        // Realiza una busqueda en la bd
        public function seleccionar($sql, $parametros){
        
            $sentencia = $this->conexion->prepare($sql);
            
            $sentencia -> execute($parametros);

            $tupla = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            return $tupla;
        }

        // Realiza un borrado en la bd
        public function borrar($sql, $parametros){
            
            $sentencia = $this->conexion->prepare($sql);

            $sentencia -> execute($parametros);

        }

        // Realiza una modificación en la bd
        public function modificar($sql, $parametros){

            $sentencia = $this->conexion->prepare($sql);

            $sentencia -> execute($parametros);

            $tuplas = $sentencia->rowCount();

            return $tuplas;

        }

    }