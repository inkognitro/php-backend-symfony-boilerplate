<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema\Parameter;

final class ParameterValidator
{
    /**
     * @value $parameter
     * @return null|string|array
     */
    public function validate(ParameterSchema $parameterSchema, $parameter)
    {
        if($parameterSchema instanceof StringParameterSchema) {
            return $this->validateStringParameter($parameterSchema, $parameter);
        }

        if($parameterSchema instanceof ObjectParameterSchema) {
            return $this->validateObjectParameter($parameterSchema, $parameter);
        }

        throw new ParameterSchemaNotSupportedException(get_class($parameterSchema));
    }

    /** @value $parameter */
    private function validateStringParameter(StringParameterSchema $schema, $parameter): ?string
    {
        if(!is_string($parameter)) {
            return 'must be a string';
        }
        return null;
    }

    /**
     * @value $parameter
     * @return null|string|array
     */
    private function validateObjectParameter(ObjectParameterSchema $schema, $parameter)
    {
        if(!is_array($parameter)) {
            return 'must be an object';
        }
        $errors = [];
        foreach($schema->getProperties() as $property) {
            if($property->isRequired() && !array_key_exists($property->getName(), $parameter)) {
                $errors[$property->getName()] = 'is required';
                continue;
            }
            $propertyParameter = $parameter[$property->getName()];
            $error = $this->validate($property->getPropertySchema(), $propertyParameter);
            if($error !== null) {
                $errors[$property->getName()] = $error;
            }
        }
        return (count($errors) === 0 ? null : $errors);
    }
}