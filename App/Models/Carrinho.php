<?php

namespace App\Models;

use MF\Model\Model;
use PDO;

class Carrinho extends Model
{

    private $hamburguer_id;
    private $usuario_id;

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    function getAll()
    {
        $query = '
            SELECT
                h.id,
                h.nome,
                h.descricao,
                c.valor,
                c.quantidade
            FROM
                carrinho as c
            LEFT JOIN
                hamburguer as h ON (c.id_hamburguer = h.id)
            WHERE
                c.id_usuario = :usuario_id
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':usuario_id', $this->__get('usuario_id'));
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function verificaHamburguer()
    {
        $query = '
            SELECT 
                *
            FROM
                carrinho
            WHERE
                id_usuario = :usuario_id AND
                id_hamburguer = :hamburguer_id
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':usuario_id', $this->__get('usuario_id'));
        $stmt->bindValue(':hamburguer_id', $this->__get('hamburguer_id'));
        $stmt->execute();


        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function adicionar()
    {
        $query = '
            INSERT INTO carrinho(id_usuario, id_hamburguer, quantidade, valor)
            VALUES(
                :usuario_id,
                :hamburguer_id,
                1,
                (SELECT valor FROM hamburguer WHERE id = :hamburguer_id)
            )
        ';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':usuario_id', $this->__get('usuario_id'));
        $stmt->bindValue(':hamburguer_id', $this->__get('hamburguer_id'));
        $stmt->execute();
    }

    function updateQuantidade() {
        $query = '
            UPDATE 
                carrinho
            SET 
                quantidade = quantidade + 1, 
                valor = (SELECT valor FROM hamburguer WHERE id = :hamburguer_id) * (quantidade)
            WHERE 
                id_usuario = :usuario_id AND id_hamburguer = :hamburguer_id;
            
            SELECT * FROM carrinho WHERE id_usuario = :usuario_id AND id_hamburguer = :hamburguer_id
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':hamburguer_id', $this->__get('hamburguer_id'));
        $stmt->bindValue(':usuario_id', $this->__get('usuario_id'));
        $stmt->execute();
        $stmt->closeCursor();

    }

    function hamburguerAtualizado() {
        $query = '
                SELECT 
                    * 
                FROM 
                    carrinho 
                WHERE 
                    id_usuario = :usuario_id AND id_hamburguer = :hamburguer_id
            ';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':hamburguer_id', $this->__get('hamburguer_id'));
        $stmt->bindValue(':usuario_id', $this->__get('usuario_id'));
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
