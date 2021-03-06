<?php
/**
 * This file is part of the Ray.FakeContextParam package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\FakeContextParam;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Ray\FakeContextParam\Annotation\Fake;

class FakeContextParamModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(Fake::class),
            [FakeContextParamInterceptor::class]
        );
        $this->bind(Reader::class)->to(AnnotationReader::class)->in(Scope::SINGLETON);
        $this->bind(Cache::class)->to(ArrayCache::class)->in(Scope::SINGLETON);
    }
}
