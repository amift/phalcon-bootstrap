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

namespace Stub\Service;

use Phalcon\Annotations\Adapter\Memory as Service;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;

class Annotation implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(DiInterface $di) : void
    {
        $di->setShared(
            'annotations',
            function () {
                $config = $this->getConfig();

                if ($config->debug) {
                    $service = new Service();
                } else {
                    $adapter = 'Phalcon\\Annotations\\Adapter\\' . $config->annotations->adapter;
                    $service = new $adapter($config->annotations->options->toArray());
                }

                return $service;
            }
        );
    }
}
