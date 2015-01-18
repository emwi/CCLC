<?php

namespace Anax\DI;

class CDIFactory extends CDIFactoryDefault
{
    public function __construct()
    {
        parent::__construct();
 
        $this->setShared('db', function() {
            $db = new \Mos\Database\CDatabaseBasic();
            $db->setOptions(require ANAX_APP_PATH . 'config/database_mysql.php');
            $db->connect();
            return $db;
        });

        $this->set('UsersController', function() {
            $controller = new \Anax\Users\UsersController();
            $controller->setDI($this);
            return $controller;
        });
        
        $this->set('form', '\Mos\HTMLForm\CForm');

    }
} 
