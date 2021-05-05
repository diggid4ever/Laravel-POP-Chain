# Laravel POP Chain Collection
关于Laravel链子的总结和exp收集，建议配合博客的[文章](https://blog.diggid.top/2021/04/20/Laravel-5-7-7-%E5%8F%8D%E5%BA%8F%E5%88%97%E5%8C%96%E7%B3%BB%E5%88%97%E6%BC%8F%E6%B4%9E%E6%B1%87%E6%80%BB/)食用~~

## Introduction
挖掘、收集、总结关于Laravel的一些链子，目前完成了
- Laravel 5.7-5.8 的大部分可以RCE/webshell的链子
- Laravel 7 的大部分可以RCE/webshell的链子

待办：
- Laravel 8 debug模式下的phar&SSRF利用的链子 
- Laravel 其他版本可能存在的链子(尝试挖掘其他RCE/非RCE，如SQL注入之类的链子)

# Summary & Useful Classes
**这里的pop链按照java反序列化的Source => Intermediate Class => Sink的思路去分析，能比较清晰的能找到链子，抓住敏感的函数和写法，回溯可控参数，设置相应属性使得代码执行到Sink处，重点注意各种写法对于可控参数的影响，思路不局限于RCE，还可以写shell，RCE时不局限于`system`函数，还可以利用`array_udiff`等数组类的执行函数，这样就不会局限于参数的限制。**

## Source

和java反序列化不一样，php反序列化的切入口(source)基本都是固定的`__destruct`，我们只需要寻找合适的即可，通过上面的挖掘总结出来合适的`__destruct`的条件有：

- 所属类没有被`__wakeup`搅屎

- 当前方法或跟进一两个方法中存在这几种形式：

```
$this->xxx->yyy($this->zzz) //常见
$this->xxx->yyy(zzz) 		//参数不可控，可能有影响，也可能没影响(__call跳板不使用传入的参数) 
$this->xxx->{$this->yyy}($this->zzz) //最佳，可以省略找中间类直接到Sink
```

- 有`$this->xxx(...)`的形式并且能一直跟进到RCE(像5.8版本的**pop2没调用中间魔术方法直接RCE的很少**)

目前找到比较好用且能用的切入口有(可能会继续补充)：

### 配合 __call

```php
1.PendingBroadcast
    public function __destruct()
    {
        $this->events->dispatch($this->event);
    }

2.PendingResourceRegistration
    public function __destruct()
    {
        if (! $this->registered) {
            $this->register();
        }
    }
    public function register()
    {
        $this->registered = true;
		// $this->registrar太完美了
        return $this->registrar->register(
            $this->name, $this->controller, $this->options
        );
    }
```



### 直接 RCE

```php
\Symfony\Component\Cache\Adapter\TagAwareAdapter
    public function __destruct()
    {
        $this->commit();
    }

pop chain:
\Symfony\Component\Cache\Adapter\TagAwareAdapter::__destruct
	\Symfony\Component\Cache\Adapter\TagAwareAdapter::commit
		\Symfony\Component\Cache\Adapter\TagAwareAdapter::invalidateTags
			\Symfony\Component\Cache\Adapter\ProxyAdapter::saveDeferred
				\Symfony\Component\Cache\Adapter\ProxyAdapter::doSave
					-> ($this->setInnerItem)($innerItem, $item);
```



## Intermediate Class

中间类(Intermediate Class)，起衔接和跳板的作用。这里比较好用的是以`__call`方法为核心的中间类，可能也会有`__invoke`方法。结合`__destruct`的`$this->xxx->yyy($this->zzz)`形式，我们便可以调用合适类的`__call($method,$args)`方法，`$method`就是`yyy`，`$args`是`yyy`函数的参数构成的参数数组，因此我们还需要考虑`$method`和`$args`的可控性对`__call`的影响。总结有下面的几个比较好用

> 适用范围：
>
> 必要组件：Symfony/symfony(第四个类)

### `__call 跳板`

```php
1.\Illuminate\View\InvokableComponentVariable
    public function __call($method, $parameters)
    {
        return $this->__invoke()->{$method}(...$parameters);
    }

	public function __invoke()
    {
        return call_user_func($this->callable);//这里又是调用任意类的无参数函数
    }

2.\Mockery\HigherOrderMessage
    public function __call($method, $args)
    {
        if ($this->method === 'shouldNotHaveReceived') {
            return $this->mock->{$this->method}($method, $args);
        }

        $expectation = $this->mock->{$this->method}($method); //调用任意类的单参数函数，用LazyOption类的filter方法可以写shell
        return $expectation->withArgs($args);
    }

3.\Symfony\Component\Cache\Traits\RedisClusterProxy
    public function __call(string $method, array $args)
    {
        $this->redis ?: $this->redis = $this->initializer->__invoke(); //主动调用__invoke,又回到第一个，也可以去找其他的

        return $this->redis->{$method}(...$args);
    }
```

### `__call 非跳板`

非跳板即不同找Sink，直接顺着找即可调用到RCE的部分

```php
// Validator这个类比较特殊，即可当跳板，也可直接RCE
4.\Illuminate\Validation\Validator
    public function __call($method, $parameters)
    {
        $rule = Str::snake(substr($method, 8));

        if (isset($this->extensions[$rule])) {
            return $this->callExtension($rule, $parameters);//5.8版本pop2的链
        }
    	...
    }
    protected function callExtension($rule, $parameters)
    {
        $callback = $this->extensions[$rule];

        if (is_callable($callback)) {
            // 5.8
           	return call_user_func_array($callback, $parameters);
            // 7
			return $callback(...array_values($parameters))
        }
    }

5.\Illuminate\Support\Manager
    public function __call($method, $parameters)
    {
        return $this->driver()->$method(...$parameters);
    }
```

## Sink

Sink就是触发最后RCE或者其他文件读写操作的点。下面两个Sink的优势是不接受参数(不受传入参数的影响)，任意参数可控

```php
1.\PhpOption\LazyOption::option 
//__call可传无、单、双参
    private function option()
    {
        if (null === $this->option) {
            /** @var mixed */
            $option = call_user_func_array($this->callback, $this->arguments);//任意参数函数
			...
        }
        return $this->option;
    }

2.\Illuminate\Auth\RequestGuard::user
//__call只能传无参
    public function user()
    {
        if (! is_null($this->user)) {
            return $this->user;
        }
        return $this->user = call_user_func(
            $this->callback, $this->request, $this->getProvider() //双参数函数
        );
    }

3.\Illuminate\Bus\Dispatcher::dispatchToQueue
//__call传单参
    public function dispatchToQueue($command)
    {
        $connection = $command->connection ?? null;
        $queue = call_user_func($this->queueResolver, $connection);
		...
    }
```