<?php
/**
 * This file is part of the Ray.FakeModule
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\FakeModule;

use Doctrine\Common\Annotations\Reader;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Ray\Di\InjectorInterface;

class FakeClassInterceptor implements MethodInterceptor
{
    /**
     * @var InjectorInterface
     */
    private $injector;

    public function __construct(InjectorInterface $injector) {
        $this->injector = $injector;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MethodInvocation $invocation)
    {
        // Get the real class name.
        $fullClassName  = $invocation->getMethod()->class;
        $reflectClass   = new \ReflectionClass($fullClassName);
        $shortClassName = $reflectClass->getShortName();

        // Get the full fake class name.
        $fakeClassName = str_replace($shortClassName, 'Fake' . $shortClassName, $fullClassName);

        // If the fake class exists ,then invoke its method. otherwise invoke original class method.
        if (class_exists($fakeClassName)) {
            $fakeObj = $this->injector->getInstance($fakeClassName);
            $method = $invocation->getMethod()->name;
            $arguments = (array) $invocation->getArguments();

            return call_user_func_array([$fakeObj, $method], $arguments);
        }

        return $invocation->proceed();
    }
}
