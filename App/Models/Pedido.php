<?php

namespace App\Models;

use MF\Model\Model;
use PDO;

class Pedido extends Model {

    private $usuario_id;
    private $valor_total;
    private $pedido_id;
    private $hamburguer_id;
    private $quantidade;
    private $valor;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function setPedido() {

        $query = '
            INSERT INTO pedidos(id_usuario, valor_total) VALUES (:usuario_id, :valor_total);
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':usuario_id', $this->__get('usuario_id'));
        $stmt->bindValue(':valor_total', $this->__get('valor_total'));
        $stmt->execute();

        return $this->db->lastInsertId();
    }

    public function insereItem() {
        $query = '
            INSERT INTO itens_pedido(id_pedido, id_hamburguer, quantidade, valor)
            VALUES (:pedido_id, :hamburguer_id, :quantidade, :valor);
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':pedido_id', $this->__get('pedido_id'));
        $stmt->bindValue(':hamburguer_id', $this->__get('hamburguer_id'));
        $stmt->bindValue(':quantidade', $this->__get('quantidade'));
        $stmt->bindValue(':valor', $this->__get('valor'));
        $stmt->execute();
    }

    public function getAll() {
        $query = "
            SELECT
                p.id AS id_pedido,
                u.nome AS nome_usuario,
                GROUP_CONCAT(CONCAT(ip.quantidade, ' - ', h.nome) SEPARATOR ', ') AS hamburguers_pedido,
                p.valor_total as valor,
                p.status_pedido as status
            FROM
                pedidos AS p
                JOIN itens_pedido AS ip ON p.id = ip.id_pedido
                JOIN hamburguer AS h ON ip.id_hamburguer = h.id
                JOIN usuarios AS u ON p.id_usuario = u.id
                GROUP BY p.id
    
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function preparar() {
        $query = "
        UPDATE pedidos SET status_pedido = 'preparando' WHERE id = :pedido_id;
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':pedido_id', $this->__get('pedido_id'));
        $stmt->execute();
    }

    public function pronto() {
        $query = "
            UPDATE pedidos SET status_pedido = 'pronto' WHERE id = :pedido_id;
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':pedido_id', $this->__get('pedido_id'));
        $stmt->execute();
    }

    public function finalizar() {
        $query = "
            UPDATE pedidos SET status_pedido = 'finalizado' WHERE id = :pedido_id;
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':pedido_id', $this->__get('pedido_id'));
        $stmt->execute();
    }

    public function apagar() {
        $query1 = "DELETE FROM itens_pedido WHERE id_pedido = :pedido_id;";
        $query2 = "DELETE FROM pedidos WHERE id = :pedido_id;";
    
        $stmt = $this->db->prepare($query1);
        $stmt->bindValue(':pedido_id', $this->__get('pedido_id'));
        $stmt->execute();
    
        $stmt = $this->db->prepare($query2);
        $stmt->bindValue(':pedido_id', $this->__get('pedido_id'));
        $stmt->execute();
    }

    public function getMeusPedidos() {
        $query = "
        SELECT
            p.id AS id_pedido,
            u.nome AS nome_usuario,
            CONCAT('[', GROUP_CONCAT(JSON_OBJECT('nome', h.nome, 'quantidade', ip.quantidade)), ']') AS hamburguers_pedido,
            p.valor_total AS valor,
            p.status_pedido AS status
        FROM
            pedidos AS p
            JOIN itens_pedido AS ip ON p.id = ip.id_pedido
            JOIN hamburguer AS h ON ip.id_hamburguer = h.id
            JOIN usuarios AS u ON p.id_usuario = u.id
        WHERE
            p.id_usuario = :usuario_id AND p.status_pedido != 'finalizado'
        GROUP BY 
            P.data DESC;

        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':usuario_id', $this->__get('usuario_id'));
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

}