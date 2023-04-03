<?php
    namespace App\Controllers;
    
    //Recursos do framework
    use \MF\Controller\Action;
    use \MF\Model\Container;

    class AuthController extends Action {
        public function autenticar() {
            $usuario = Container::getModel('Usuario');

            $usuario->__set('email', $_POST['email']);
            $usuario->__set('senha', $_POST['senha']);

            $usuario->autenticar();

            if(!empty($usuario->__get('id')) && !empty($usuario->__get('nome'))){
                session_start();

                $_SESSION['id'] = $usuario->__get('id');
                $_SESSION['nome'] = $usuario->__get('nome');
                $_SESSION['tipo'] = $usuario->__get('tipo');

                header('Location: /home');

            } else {
                header('LOCATION: /?login=erro');
            }
        }

        public function cadastrar() {
            $usuario = Container::getModel('Usuario');

            $usuario->__set('nome', $_POST['nome']);
            $usuario->__set('email', $_POST['email']);
            $usuario->__set('senha', $_POST['senha']);

            if($usuario->validaCadastro()) {
                if(empty($usuario->getUsuarioPorEmail())) {
                    $usuario->salvar();
                    $this->autenticar();
                } else {
                    echo 'não está vazio';
                }
            }
        }

        public function sair() {
            session_start();
            session_destroy();
            header('LOCATION: /');
        }
    }
?>