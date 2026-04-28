<?php
require_once('./servicios/bd.php');
class Personaje implements JsonSerializable{
    
    private $id;
    private $nombre;
    private $nivel;
    private $puntos_vida;
    private $raza;
    private $bd;

    public function __construct($nombre, $nivel, $puntos_vida, $raza = null) {
        $this->id = null;
        $this->nombre = $nombre;
        $this->nivel = $nivel;
        $this->puntos_vida = $puntos_vida;
        $this->raza= $raza;
        $this->bd = new BD();
    }

    function jsonSerialize(): mixed{
        try{
            return [
                "nombre" => $this->nombre,
                "nivel" => $this->nivel,
                "puntos_vida" => $this->puntos_vida,
                "raza" => $this->raza
            ];
        }
        catch(Throwable $e){
            http_response_code(500);
            echo "HTTP/2 500 Internal Server Error " . $e->getMessage() . " ";
            die();
        }
    }

    public function __toString(){
        return $this->id . " " . $this->nombre . " " . $this->nivel . " " . $this->puntos_vida . " " . $this->raza . " ";
    }

    public static function borrar($id){
        try{
            $sql = "DELETE FROM personaje WHERE id = :id";
            $bd = new BD();
            $bd->borrar($sql, $id);
        }
        catch(Throwable $e){
            http_response_code(500);
            echo "HTTP/2 500 Internal Server Error " . $e->getMessage() . " ";
            die();
        }
    }

    public function insertar() {
        try{
            $sql="INSERT INTO personaje (nombre, nivel, puntos_vida) VALUES (?,?,?)";
            $parametros = [$this->nombre, $this->nivel, $this->puntos_vida];
            $id = $this->bd->insertar($sql, $parametros);   
            
            return $id;
        }
        catch(Throwable $e){
                /* header("HTTP/2 500 Server Error");
                echo "Error en controlador personaje en listar ". $e . " <br>"; */
            throw  new  Exception("Error en personaje en insertar ". $e . " <br>");
        }
    }

    public static function listar(){
        try{
            
            $sql = "SELECT p.id, p.nombre, p.nivel, p.puntos_vida, h.profesion, e.tipo, o.clan
                    FROM personaje as p 
                    LEFT JOIN humano as h ON h.id_personaje = p.id
                    LEFT JOIN elfo as e ON e.id_personaje = p.id
                    LEFT JOIN orco as o ON o.id_personaje = p.id";
            $bd = new BD();
            
            $tuplas = $bd->seleccionar($sql, null);
            
            $personajes = [];
            foreach ($tuplas as $tupla){

                $id_tupla = $tupla['id'];
                $raza = "";
                if(isset($tupla["profesion"])){
                    // Es humano
                    $raza = "humano";
                }
                elseif(isset($tupla["tipo"])){
                    // Es elfo
                    $raza = "elfo";
                }
                elseif(isset($tupla["clan"])){
                    // Es orco
                    $raza = "orco";
                }
                $personaje = new Personaje($tupla['nombre'], $tupla['nivel'], $tupla['puntos_vida'], $raza);
                $personaje->id = $id_tupla;
                array_push($personajes, $personaje);
            }
            return $personajes; 
        }
        catch(Throwable $e){
            echo "hola";
            http_response_code(500);
            echo "HTTP/2 500 Internal Server Error " . $e->getMessage() . " ";
            die();
        }
    }
}