<?php

namespace Sungmee\Admini;

class Admini {
    public $config;

    public function __construct()
    {
        $this->config = config('admini');
    }

    public function auth()
    {
        $email = $this->config['email'];
        return $email == session('auth') ? $email : false;
    }

    public function attempt($req)
    {
        return $req['email'] == $this->config['email'] && $req['password'] == $this->config['password'];
    }
}