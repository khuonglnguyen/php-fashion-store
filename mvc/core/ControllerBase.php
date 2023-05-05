<?php
class ControllerBase{

    public function model($model){
        require_once "./mvc/models/".$model.".php";
        $instance = $model::getInstance();
        return $instance;
    }

    public function view($view, $data=[]){
        require_once "./mvc/views/".$view.".php";
    }

    public function redirect($controller,$method = "index",$args = array())
    {
        global $core; /* Guess Obviously */

        $location = $core->config->base_url . "/" . $controller . "/" . $method . "/" . implode("/",$args);

        /*
            * Use @header to redirect the page:
        */
        header("Location: " . URL_ROOT . $location);
        exit;
    }
}
?>