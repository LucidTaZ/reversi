<?php

namespace lucidtaz\reversi\core;

use LogicException;
use Psr\Log\LoggerInterface;
use Silex\Application as SilexApplication;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Wrapper for automatic bootstrapping and type-strict access of services
 *
 * @property-read SessionInterface $session
 * @property-read LoggerInterface $logger
 */
class Application extends SilexApplication
{
    public function __construct()
    {
        parent::__construct();

        $this->register(new MonologServiceProvider());
        /* @var $monolog \Monolog\Logger */
        $monolog = $this['monolog'];
        $monolog->pushHandler(new \Monolog\Handler\StreamHandler('php://stderr'));

        $this->register(new SessionServiceProvider());
    }

    public function __get($name)
    {
        static $propertyMap = null;
        if ($propertyMap === null) {
            $propertyMap = [
                'logger' => 'monolog',
            ];
        }

        if (isset($this[$name])) {
            return $this[$name];
        }
        if (isset($this[$propertyMap[$name]])) {
            return $this[$propertyMap[$name]];
        }
        throw new LogicException('Undefined property ' . $name);
    }
}