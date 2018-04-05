<?php

namespace App\DateTime\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use App\DateTime\Date\Date;
use App\DateTime\DateTime;
use App\DateTime\Time\Time;

class DateTimeType extends Type
{
    const KUTNY_DATETIME = 'kutnyDateTime';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDateTimeTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null) ? $value->toFormat($platform->getDateTimeFormatString()) : null;
    }

    public function getName()
    {
        return self::KUTNY_DATETIME;
    }
    
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof \DateTime) {
            return $value;
        }

        $dateTime = date_create_from_format($platform->getDateTimeFormatString(), $value);

        if (!$dateTime) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeFormatString());
        }

        $timestamp = $dateTime->getTimestamp();

        return new DateTime(
            new Date(
                date('Y', $timestamp),
                date('m', $timestamp),
                date('d', $timestamp)
            ),
            new Time(
                date('H', $timestamp),
                date('i', $timestamp),
                date('s', $timestamp) + ($timestamp - (int)$timestamp)
            )
        );
    }

    public function getBindingType()
    {
        return \PDO::PARAM_STR;
    }
}
