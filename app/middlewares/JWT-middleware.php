<?php

class AuthMiddleware {
    public function run($request, $response) {
        $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
        $auth_header = explode(' ', $auth_header);
        if(count($auth_header) != 2) {
            return;
        }
        if($auth_header[0] != 'Bearer') {
            return;
        }
        $jwt = $auth_header[1];
        $response->user = validarJWT($jwt);
    }
}