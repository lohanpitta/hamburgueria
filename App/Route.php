<?php
    namespace App;
    
    use \MF\Init\Bootstrap;

    class Route extends Bootstrap {

        public function initRoutes() {

            $routes['index'] = Array(
                'route' =>'/',
                'controller' => 'indexController',
                'action' => 'index'
            );

            $routes['home'] = Array(
                'route' =>'/home',
                'controller' => 'AppController',
                'action' => 'home'
            );

            $routes['item'] = Array(
                'route' =>'/item',
                'controller' => 'AppController',
                'action' => 'item'
            );

            $routes['autenticar'] = Array(
                'route' =>'/autenticar',
                'controller' => 'AuthController',
                'action' => 'autenticar'
            );

            $routes['sair'] = Array(
                'route' =>'/sair',
                'controller' => 'AuthController',
                'action' => 'sair'
            );

            $routes['user'] = Array(
                'route' =>'/user',
                'controller' => 'AppController',
                'action' => 'user'
            );

            $routes['registrar-ingrediente'] = Array(
                'route' =>'/registrar-ingrediente',
                'controller' => 'AppController',
                'action' => 'registrarIngrediente'
            );

            $routes['registrar-hamburguer'] = Array(
                'route' =>'/registraHamburguer',
                'controller' => 'AppController',
                'action' => 'registrarHamburguer'
            );

            $routes['remover-ingrediente'] = Array(
                'route' =>'/remover-ingrediente',
                'controller' => 'AppController',
                'action' => 'removerIngrediente'
            );

            $routes['editar-ingrediente'] = Array(
                'route' =>'/editar-ingrediente',
                'controller' => 'AppController',
                'action' => 'editarIngrediente'
            );

            $routes['carrinho'] = Array(
                'route' =>'/carrinho',
                'controller' => 'CarrinhoController',
                'action' => 'carrinho'
            );

            $routes['adicionar-carrinho'] = Array(
                'route' =>'/adicionar-carrinho',
                'controller' => 'CarrinhoController',
                'action' => 'adicionarAoCarrinho'
            );

            $routes['remover-carrinho'] = Array(
                'route' =>'/remover-carrinho',
                'controller' => 'CarrinhoController',
                'action' => 'removerDoCarinho'
            );

            $this->setRoutes($routes);
        }

    }
?>