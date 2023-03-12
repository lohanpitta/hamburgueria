<?php

namespace App\Controllers;

//Recursos do framework
use \MF\Controller\Action;
use \MF\Model\Container;

class CarrinhoController extends Action {

    public function carrinho() {
        session_start();
        
        $carrinho = Container::getModel('Carrinho');

        $carrinho->__set('usuario_id', $_SESSION['id']);
        
        // echo '<pre>';
        // print_r($carrinho->getAll());
        // echo '</pre>';

        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo $carrinho->getAll();
            exit;
        }

        $this->view->hamburguers = $carrinho->getAll();

        $this->render('carrinho');
    }

    public function adicionarAoCarrinho() {
        session_start();

        $carrinho = Container::getModel('Carrinho');
        
        $carrinho->__set('hamburguer_id', $_POST['id_hamburguer']);
        $carrinho->__set('usuario_id', $_SESSION['id']);
        $verificacao = $carrinho->verificaHamburguer();

        if($verificacao) {
            $carrinho->updateQuantidade();
        } else {
            $carrinho->adicionar();
        }

        echo json_encode($carrinho->hamburguerAtualizado());
        
        // echo 'Seu hamburguer foi adicionado ao carrinho!!!';
    }
    
    
}

?>