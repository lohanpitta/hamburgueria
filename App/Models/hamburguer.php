<?php

namespace App\Models;

use MF\Model\Model;
use PDO;

class Hamburguer extends Model
{

    private $nome;
    private $descricao;
    private $ingrediente;

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function getAll()
    {
        $query = "
        SELECT 
            h.id, h.nome, h.descricao, GROUP_CONCAT(i.nome SEPARATOR ', ') AS ingredientes
        FROM 
            hamburguer h
            INNER JOIN hamburguer_ingredientes hi ON h.id = hi.hamburguer_id
            INNER JOIN ingredientes i ON hi.ingrediente_id = i.id
        GROUP BY 
            h.id   
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHamburguer($id)
    {

        $query = "
        SELECT 
            h.id, h.nome, h.descricao, GROUP_CONCAT(i.nome SEPARATOR ', ') AS ingredientes
        FROM 
            hamburguer h
            INNER JOIN hamburguer_ingredientes hi ON h.id = hi.hamburguer_id
            INNER JOIN ingredientes i ON hi.ingrediente_id = i.id
        WHERE
            h.id = :id
        GROUP BY 
            h.id   
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getIngredientes()
    {
        $query = '
            SELECT
                nome, id
            FROM
                ingredientes
        ';

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarIngrediente()
    {
        $query = '
            INSERT INTO ingredientes(nome)VALUES(:ingrediente)
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':ingrediente', $this->__get('ingrediente'));
        $stmt->execute();

        header('LOCATION: /user');
    }

    public function registrarHamburguer() {

        print_r($this->ingrediente);
        
        $query = '
            INSERT INTO hamburguer(nome, descricao)VALUES(:nome, :descricao);
            SELECT LAST_INSERT_ID() as novo_id;
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':descricao', $this->__get('descricao'));
        $stmt->execute();

        $hamburguer_id = $this->db->lastInsertId();
        foreach ($this->__get('ingrediente') as $chave => $valor) {
            $query2 = '
                INSERT INTO hamburguer_ingredientes(hamburguer_id, ingrediente_id)VALUES(:hamburguer_id, :ingrediente_id)
            ';

            $stmt = $this->db->prepare($query2);
            $stmt->bindValue(':hamburguer_id', $hamburguer_id);
            $stmt->bindValue(':ingrediente_id', $valor);
            $stmt->execute();
        }

        return true;
    }
}
