<?php
require_once './app/models/api-user-model.php';
require_once './app/views/api-view.php';
require_once './libs/jwt.php';

class UserController {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new UserModel();
        $this->view = new JsonView();
    }

    public function getToken() {
        $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
        $auth_header = explode(' ', $auth_header);
        if(count($auth_header) !=2) {
            return $this->view->response("Error en datos ingresados", 400);
        }
        if($auth_header[0] != 'Basic') {
            return $this->view->response('Error en datos ingresados', 400);
        }
        $user_pass = base64_decode($auth_header[1]);
        $user_pass = explode(':', $user_pass);
        $user = $this->model->getUser($user_pass[0]);
        if($user == null || !password_verify($user_pass[1], $user->password)) {
            return $this->view->response('Error en datos ingresados', 400);
        }

        //Generamos el token
        $token = crearJWT(array(
            'sub'=> $user->ID_Usuario,
            'nombre' => $user->Nombre,
            'role' =>'admin',
            'iat' =>time(),
            'exp' => time() + 60,
            'saludo' =>'Hola',
        ));
        return $this->view->response($token);
    }
}
