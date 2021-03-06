<?php

namespace TheCocktail\EzExtraMediaBundle\FieldType\EzExtraMedia;

use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\FieldType\ValidationError;
use eZ\Publish\SPI\FieldType\Value as SPIValue;
use eZ\Publish\Core\FieldType\Value as BaseValue;

class Type extends FieldType
{
    public function validateValidatorConfiguration($validatorConfiguration)
    {
        $validationErrors = array();

        return $validationErrors;
    }

    public function validate(FieldDefinition $fieldDefinition, SPIValue $fieldValue)
    {
        $validationErrors = array();

        if ($this->isEmptyValue($fieldValue)) {
            return $validationErrors;
        }

        //Youtube url validation
        if (!preg_match(
            '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
            $fieldValue
        )) {
            $validationErrors[] = new ValidationError("Invalid YouTube url", null);
        }

        return $validationErrors;
    }

    public function getFieldTypeIdentifier()
    {
        return 'ezextramedia';
    }

    public function getName(SPIValue $value)
    {
        return (string)$value->text;
    }

    public function getEmptyValue()
    {
        return new Value();
    }

    public function isEmptyValue(SPIValue $value)
    {
        return $value->text === null || trim($value->text) === '';
    }

    protected function createValueFromInput($inputValue)
    {
        if (is_string($inputValue)) {
            $inputValue = new Value($inputValue);
        }

        return $inputValue;
    }

    protected function checkValueStructure(BaseValue $value)
    {
    }

    protected function getSortInfo(BaseValue $value)
    {
        return $this->transformationProcessor->transformByGroup((string)$value, 'lowercase');
    }

    public function fromHash($hash)
    {
        if ($hash === null) {
            return $this->getEmptyValue();
        }

        return new Value($hash);
    }

    public function toHash(SPIValue $value)
    {
        if ($this->isEmptyValue($value)) {
            return null;
        }

        return $value->text;
    }

    public function isSearchable()
    {
        return true;
    }
}
