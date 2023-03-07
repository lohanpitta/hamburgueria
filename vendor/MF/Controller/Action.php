<?php

namespace MF\Controller;

use stdClass;

abstract class Action {
    protected $view;

    public function __construct() {
        $this->view = new \stdClass();
    }

    protected function render($view, $layout = 'layout') {
        $this->view->page = $view;

        $caminho = "../App/Views/" . $layout . ".phtml";
        
        if(file_exists($caminho)) { //Caso o layout passado não exista, será renderizado o conteudo padrão
            require_once $caminho;
        } else {
            $this->content();
        }
    }

    protected function content() {

        $classAtual = get_class($this);
        $classAtual = str_replace('App\\Controllers\\', '', $classAtual);
        $classAtual = strtolower(str_replace('Controller', '', $classAtual));

        require_once '../App/Views/' . $classAtual . '/' . $this->view->page . '.phtml';
    }
}
?>