<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\DependencyInjection;

use App\WebApiV1Bundle\Endpoints\Endpoints;
use App\WebApiV1Bundle\RoutingLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class WebApiV1Extension extends Extension
{
    private const ENDPOINT_TAG = 'web_api_v1.endpoint';

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../'));
        $loader->load('services.yaml');
        $container->register(Endpoints::class)->addArgument(new TaggedIteratorArgument(self::ENDPOINT_TAG));
        $container->autowire(RoutingLoader::class)->addTag('routing.loader');
    }
}