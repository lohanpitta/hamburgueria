<?php

namespace App\Controllers;

//Recursos do framework
use \MF\Controller\Action;
use \MF\Model\Container;

class CarrinhoController extends Action {

    public function carrinho() {
        $this->render('carrinho');
    }

    public function adicionarAoCarrinho() {

    }


}

?>