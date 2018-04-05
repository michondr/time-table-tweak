<?php

namespace App\DateTime\Doctrine;

use App\DateTime\Time\Time;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class TimeType extends Type
{
    const MICHONDR_TIME = 'michondr_time';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getTimeTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null) ? $value->toFormat($platform->getTimeFormatString()) : null;
    }

    public function getName()
    {
        return self::MICHONDR_TIME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof Time) {
            return $value;
        }

        $time = date_create_from_format($platform->getTimeFormatString(), $value);

        if (!$time) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getTimeFormatString());
        }

        $timestamp = $time->getTimestamp();

        return new Time(
            date('H', $timestamp),
            date('i', $timestamp),
            date('s', $timestamp) + ($timestamp - (int)$timestamp)
        );
    }

    public function getBindingType()
    {
        return \PDO::PARAM_STR;
    }
}
