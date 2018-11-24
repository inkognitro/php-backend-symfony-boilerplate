<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Command\Validation;

use Symfony\Component\DependencyInjection\Container;

final class Validator
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getMessageFromValidation(array $data, array $ruleClassNames): ?Message
    {
        foreach ($ruleClassNames as $ruleClassName) {
            /** @var $rule Rule */
            $rule = $this->container->get($ruleClassName);
            $message = $rule->getMessageFromValidation($data);
            if($message !== null) {
                return $message;
            }
        }
        return null;
    }
}