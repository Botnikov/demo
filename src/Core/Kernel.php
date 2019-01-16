<?php
/**
 * Created by PhpStorm.
 * User: Botnikov Aleksandr
 * Date: 10.01.19
 * Time: 23:11
 */

namespace Core;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Zend\ServiceManager\ServiceManager;

class Kernel
{

    private $container;

    private static $object;

    private function __construct()
    {
    }

    private function __clone()
    {
    }


    /**
     * @return ServiceManager
     */
    public function getContainer(): ServiceManager
    {
        return $this->container;
    }

    public function get($name)
    {
        return $this->getContainer()->get($name);
    }

    public static function boot(): self
    {
        if (!self::$object) {
            self::$object = new self();
        }

        if (!self::$object->container) {
            self::$object->bootContainer();
            AnnotationRegistry::registerLoader('class_exists');

            self::$object->loadServices('App\\Http\\Controller');
            self::$object->loadServices('App\\Http\\Controller\\Auth');
        }

        return self::$object;
    }

    /**
     *
     */
    public function handleRequest()
    {
        $response = null;

        $request = $this->get(ServerRequestInterface::class);

        $routerContainer = $this->get(\Aura\Router\RouterContainer::class);

        /**@var \Aura\Router\Matcher $matcher */
        $matcher = $routerContainer->getMatcher();

        $route = $matcher->match($request);


        if ($route) {
            foreach ($route->attributes as $key => $val) {
                $request = $request->withAttribute($key, $val);
            }

            $handler = $route->handler;

            if (is_array($handler) && $this->getContainer()->has($handler['service'])) {
                $service = $this->get($handler['service']);
                if (array_key_exists('action', $handler) && method_exists($service, $handler['action'])) {
                    $response = $service->{$handler['action']}();
                }

            } else if ($handler instanceof \Closure) {
                $response = $handler();
            }
        }


        if (!$response instanceof ResponseInterface) {
            $response = new HtmlResponse('Page Not Found', 400);
        }

        $emitter = $this->getContainer()->get(SapiStreamEmitter::class);
        $emitter->emit($response);
        exit;
    }

    /**
     * @param string $namespace
     * @param \Closure|null $callback
     * @throws \ReflectionException
     */
    public function loadServices(string $namespace, ?\Closure $callback = null): void
    {
        $baseDir = dirname(__DIR__) . '/';

        $actualDirectory = str_replace('\\', '/', $namespace);

        $actualDirectory = $baseDir . $actualDirectory;

        $files = array_filter(scandir($actualDirectory), function ($file) use ($actualDirectory) {
            return $file !== '.' && $file !== '..' && !is_dir($actualDirectory . DIRECTORY_SEPARATOR . $file);
        });

        foreach ($files as $file) {
            $class = new ReflectionClass($namespace . '\\' . basename($file, '.php'));
            $serviceName = $class->getName();

            $this->getContainer()->setFactory($serviceName, ReflectionBasedAbstractFactory::class);

            if ($callback) {
                $callback($serviceName, $class);
            }
        }
    }

    /**
     * @return $this
     */
    private function bootContainer(): self
    {
        $this->container = new ServiceManager($this->loadConfigs() ?? []);
        return $this;
    }

    /**
     * @return array|null
     */
    private function loadConfigs(): ?array
    {

        $aggregator = new ConfigAggregator([
            new PhpFileProvider('config/{{,*.}global}.php'),
        ]);
        return $aggregator->getMergedConfig();
    }
}