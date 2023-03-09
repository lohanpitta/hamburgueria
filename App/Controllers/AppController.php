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

        if(empty($_POST['nome']) || empty($_POST['descricao']) || empty($_POST['ingredientes'])){
           header('LOCATION: /user?hamburguers&erro');
        }

        $db = Container::getModel('hamburguer');
        $db->__set('nome', $_POST['nome']);
        $db->__set('descricao', $_POST['descricao']);
        $db->__set('ingrediente', $_POST['ingredientes']);


        if($db->registrarHamburguer()) {
            header('LOCATION: /user?hamburguers');
        }
    }

    public function removerIngrediente() {

        $db = Container::getModel('hamburguer');
        $db->__set('ingrediente', $_GET['id']);
       
        if( $db->removerIngrediente()) {
            header('LOCATION: /user');
        }
    }

    public function editarIngrediente() {
        $db = Container::getModel('hamburguer');
        $db->__set('ingrediente', $_GET['id']);
        $db->__set('nome', $_POST['valor']);

        $db->editarIngrediente();
        header('LOCATION: /user');
    }
}
?>