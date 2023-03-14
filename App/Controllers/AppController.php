<?php

namespace App\Controllers;

//Recursos do framework
use \MF\Controller\Action;
use \MF\Model\Container;

class AppController extends Action {

    public function home(){
        $this->validaAutenticacao();

        $hamburguers = Container::getModel('hamburguer');
        $this->view->itens = $hamburguers->getAll();

        $this->render('home');
    }

    public function item(){
        $this->validaAutenticacao();

        $hamburguer = Container::getModel('hamburguer');
        $this->view->item = $hamburguer->getHamburguer($_GET['id']);
        $this->render('item');
    }
    
    public function validaAutenticacao() {

        session_start();

        if (!isset($_SESSION['id']) || empty($_SESSION['id']) || !isset($_SESSION['nome']) || empty($_SESSION['nome'])) {
            header('LOCATION: /?login=erro');
        }
    }

    public function user() {

        $this->validaAutenticacao();

        $db = Container::getModel('hamburguer');
        $listaIngredientes = $db->getIngredientes();
        $listaHamburguers = $db->getAll();

        $this->view->ingredientes = $listaIngredientes;
        $this->view->hamburguers = $listaHamburguers;

        $this->render('user', 'layout-user');
    }

    public function registrarIngrediente() {
        $this->validaAutenticacao();

        $db = Container::getModel('hamburguer');
        $db->__set('ingrediente', $_POST['nomeIngrediente']);

        $db->registrarIngrediente();

    }

    public function registrarHamburguer() {
        $this->validaAutenticacao();

        if(empty($_POST['nome']) || empty($_POST['descricao']) || empty($_POST['ingredientes'])){
           header('LOCATION: /user?hamburguers&erro');
        }

        $db = Container::getModel('hamburguer');
        $db->__set('nome', $_POST['nome']);
        $db->__set('descricao', $_POST['descricao']);
        $db->__set('ingrediente', $_POST['ingredientes']);
        
        $valor = str_replace('R$ ', '', $_POST['valor']);
        $valor = str_replace(',', '.', $valor);
        $db->__set('valor', $valor);


        if($db->registrarHamburguer()) {
            header('LOCATION: /user?hamburguers');
        }
    }

    public function removerIngrediente() {
        $this->validaAutenticacao();

        $db = Container::getModel('hamburguer');
        $db->__set('ingrediente', $_GET['id']);
       
        if( $db->removerIngrediente()) {
            header('LOCATION: /user');
        }
    }

    public function editarIngrediente() {
        $this->validaAutenticacao();

        $db = Container::getModel('hamburguer');
        $db->__set('ingrediente', $_GET['id']);
        $db->__set('nome', $_POST['valor']);

        $db->editarIngrediente();
        header('LOCATION: /user');
    }

    public function finalizar() {
        $this->validaAutenticacao();

        $db_carrinho = Container::getModel('Carrinho');
        $db_pedido = Container::getModel('Pedido');
        $usuario_id = $_SESSION['id'];

        //Recupera os hamburguers do cliente no carrinho
        $db_carrinho->__set('usuario_id', $usuario_id);
        $hamburguers = $db_carrinho->getAll();
        
        if(empty($hamburguers)) {
            return;
        }
        
        //Define os valores que serão atribuidos a coluna no pedido
        $db_pedido->__set('usuario_id', $usuario_id);
        $db_pedido->__set('valor_total', $db_carrinho->total()['total']);
        $id_pedido = $db_pedido->setPedido();
        $db_pedido->__set('pedido_id', $id_pedido);

        //Associa os hamburguers do carrinho ao pedido criado
        foreach($hamburguers as $chave => $hamburguer) {
            $db_pedido->__set('hamburguer_id', $hamburguer['id']);
            $db_pedido->__set('quantidade', $hamburguer['quantidade']);
            $db_pedido->__set('valor', $hamburguer['valor']);
            $db_pedido->insereItem();

            //remove esse hamburguer do carrinho
            $db_carrinho->__set('hamburguer_id', $hamburguer['id']);
            $db_carrinho->remover();
        }
    }
}
?>