<?php declare(strict_types=1);

namespace AppWebApiV1Bundle\DependencyInjection;

use AppWebApiV1Bundle\Endpoint\Endpoint;
use AppWebApiV1Bundle\Endpoint\Endpoints;
use AppWebApiV1Bundle\Resources\RoutingLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class AppWebApiV1Extension extends Extension
{
    private const ENDPOINT_TAG = 'app_web_api_v1.endpoint';

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yaml');

        $container->registerForAutoconfiguration(Endpoint::class)
            ->addTag(self::ENDPOINT_TAG)
            ->setAutowired(true);

        $container->register(Endpoints::class)
            ->addArgument(new TaggedIteratorArgument(self::ENDPOINT_TAG));

        $container->autowire(RoutingLoader::class)->addTag('routing.loader');
    }
}