<?php

namespace EDI\Generator;

/**
 * Class EdifactDate
 *
 * @package EDI\Generator
 */
class EdifactDate
{
    public const DATE = 102;
    public const DATE_FORMAT = 'Ymd';

    public const DATETIME = 203;
    public const DATETIME_FORMAT = 'YmdHi';

    public const SHIPPING_WEEK = 616;
    public const SHIPPING_WEEK_FORMAT = 'YW';

    public const SHIPPING_UNDEFINED = 999;
    public const SHIPPING_UNDEFINED_FORMAT = "";

    public const TYPE_ORDER_DATE = 4;
    public const TYPE_DELIVERY_DATE_REQUESTED = 2;
    public const TYPE_DELIVERY_DATE_ESTIMATED = 17;
    public const TYPE_DELIVERY_DATE_LATEST = 63;
    public const TYPE_DELIVERY_DATE_EARLIEST = 64;
    public const TYPE_DEPARTURE_DATE_ESTIMATED = 133;


    /**
     * returns an valid edifact date format
     *
     * @param string $string
     * @param int $format
     *
     * @return string
     * @throws EdifactException
     */
    public static function get($string, $format = self::DATE)
    {
        if (empty($string)) {
            return "";
        }
        switch ($format) {
            case self::DATE:
                $dateFormat = self::DATE_FORMAT;
                break;

            case self::DATETIME:
                $dateFormat = self::DATETIME_FORMAT;
                break;

            case self::SHIPPING_WEEK:
                $dateFormat = self::SHIPPING_WEEK_FORMAT;
                break;

            case self::SHIPPING_UNDEFINED:
                $dateFormat = self::SHIPPING_UNDEFINED_FORMAT;
                break;

            default:
                $dateFormat = self::DATE_FORMAT;
        }
        $dateTime = self::parseFormat($string, $format);
        if (!$dateTime) {
            throw new EdifactException('invalid date provided: ' . $string);
        }

        return $dateTime->format($dateFormat);
    }

    /**
     * @param string|\DateTime $string
     * @param integer $format
     *
     * @return bool|\DateTime
     */
    public static function parseFormat($string, $format = self::DATE)
    {
        if ($string instanceof \DateTime) {
            return $string;
        }

        $parseFormat = 'Y-m-d';
        switch ($format) {
            case self::DATE:
                $string = substr($string, 0, 10);
                $parseFormat = 'Y-m-d';
                break;
            case

            self::DATETIME:
                $parseFormat = 'Y-m-d H:i:s';
                if (strlen($string) === 16) {
                    $parseFormat = 'Y-m-d H:i';
                }
                break;
        }

        return \DateTime::createFromFormat($parseFormat, $string);
    }
}
