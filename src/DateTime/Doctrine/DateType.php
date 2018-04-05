<?php

namespace App\DateTime\Doctrine;

use App\DateTime\Date\Date;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class DateType extends Type
{
    const MICHONDR_DATE = 'michondr_date';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDateTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null) ? $value->toFormat($platform->getDateFormatString()) : null;
    }

    public function getName()
    {
        return self::MICHONDR_DATE;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof Date) {
            return $value;
        }

        $dateTime = date_create_from_format($platform->getDateFormatString(), $value);

        if (!$dateTime) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateFormatString());
        }

        $timestamp = $dateTime->getTimestamp();

        return new Date(
            date('Y', $timestamp),
            date('m', $timestamp),
            date('d', $timestamp)
        );
    }

    public function getBindingType()
    {
        return \PDO::PARAM_STR;
    }
}
