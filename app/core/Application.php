<?php

namespace lucidtaz\reversi\core;

use LogicException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Silex\Application as SilexApplication;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

/**
 * Wrapper for automatic bootstrapping and type-strict access of services
 *
 * @property-read SessionInterface $session
 * @property-read LoggerInterface $logger
 * @property-read Environment $twig
 */
class Application extends SilexApplication
{
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $this->register(new MonologServiceProvider());
        /* @var $monolog Logger */
        $monolog = $this['monolog'];
        $monolog->pushHandler(new StreamHandler('php://stderr'));

        $this->register(new SessionServiceProvider());

        $this->register(new TwigServiceProvider(), [
            'twig.path' => __DIR__ . '/../views',
        ]);
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