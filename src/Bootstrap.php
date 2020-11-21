<?php
/*
 * This source file is subject to the MIT License.
 *
 * (c) Dominic Beck <dominic@headcrumbs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this package.
 */
declare(strict_types=1);

namespace Headio\Phalcon\Bootstrap;

use Headio\Phalcon\Bootstrap\Application\Factory;
use Headio\Phalcon\Bootstrap\Application\FactoryInterface;
use Phalcon\Di\DiInterface;
use Phalcon\Http\ResponseInterface;

class Bootstrap implements BootstrapInterface
{
    /** @var FactoryInterface */
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public static function handle(DiInterface $di) : BootstrapInterface
    {
        $factory = new Factory($di);

        return new static($factory);
    }

    /**
     * Run the application
     *
     * @return ResponseInterface|bool
     */
    public function run(string $uri, ?int $context = null)
    {
        if ($context === Bootstrap::Micro) {
            return $this->factory->createForMicro()->handle($uri);
        }

        $response = $this->factory->createForMvc()->handle($uri);

        if ($response instanceof ResponseInterface) {
            return $response->send();
        }

        return $response;
    }
}
