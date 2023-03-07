<?php
    namespace App\Controllers;
    
    //Recursos do framework
    use \MF\Controller\Action;
    use \MF\Model\Container;

    class IndexController extends Action{

        public function index(){
            
            session_start();

            if (empty($_SESSION['id']) || empty($_SESSION['nome'])) {
                $this->render('index', 'layout-index');
            } else {
                header('LOCATION: /home');
            }

        }

    }
?>