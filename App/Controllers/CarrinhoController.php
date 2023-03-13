<?php

namespace App\Controllers;

//Recursos do framework

use Exception;
use \MF\Controller\Action;
use \MF\Model\Container;

class CarrinhoController extends Action {

    public function carrinho() {
        $this->validaAutenticacao();
        
        $carrinho = Container::getModel('Carrinho');

        $carrinho->__set('usuario_id', $_SESSION['id']);
        

        // if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //     echo $carrinho->getAll();
        //     exit;
        // }

        $this->view->hamburguers = $carrinho->getAll();
        $this->view->total = $carrinho->total()['total'];

        $this->render('carrinho');
    }

    public function validaAutenticacao() {

        session_start();

        if (!isset($_SESSION['id']) || empty($_SESSION['id']) || !isset($_SESSION['nome']) || empty($_SESSION['nome'])) {
            header('LOCATION: /?login=erro');
        }
    }

    public function adicionarAoCarrinho() {
        $this->validaAutenticacao();

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

    public function removerDoCarinho() {
        $this->validaAutenticacao();

        $carrinho = Container::getModel('Carrinho');

        $carrinho->__set('hamburguer_id', $_POST['id_hamburguer']);
        $carrinho->__set('usuario_id', $_SESSION['id']);

        $carrinho->removeQuantidade();

        $afetado = $carrinho->hamburguerAtualizado();
        if ($afetado[0]['quantidade'] < 1) {
            $carrinho->remover();
        }

        echo json_encode($afetado);
    }

    public function total() {
        $this->validaAutenticacao();

        $carrinho = Container::getModel('Carrinho');

        $carrinho->__set('usuario_id', $_SESSION['id']);

        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode($carrinho->total());
            exit;
        }

        return $carrinho->total();
        

    }
    
    
}

?>