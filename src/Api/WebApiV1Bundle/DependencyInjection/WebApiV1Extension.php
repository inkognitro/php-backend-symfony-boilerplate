<?php declare(strict_types=1);

namespace App\Api\WebApiV1Bundle\DependencyInjection;


use App\Api\WebApiV1Bundle\Endpoint\Endpoint;
use App\Api\WebApiV1Bundle\Endpoint\Endpoints;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class WebApiV1Extension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../')
        );
        $loader->load('services.yaml');

        $container->register(Endpoint::class)->addTag('web_api_v1.endpoint');
        $container->register(Endpoints::class)->addArgument(new TaggedIteratorArgument('web_api_v1.endpoint'));
    }
}