<?php

namespace Muse\Controller;

use Symfony\Component\HttpFoundation\Request;

class User {
    use \Simplex\Controller\EntityManagerInjector;

    public function signInAction() {
        return array();
    }

    public function loginAction(Request $request) {
        $login    = $request->get('login');
        $password = $request->get('password');

        var_dump($login, $password);

        $this->getEntityManager();

        return '';
    }
}