<?php

namespace onlinetests\Utils;

/**
 * Date and time operations
 */
class DateTime
{

    /**
     * Default date format for human
     */
    const HUMAN_FORMAT = 'j. n. Y H.i';

    /**
     * Default date format for human day month a year without time
     */
    const HUMAN_FORMAT_DATE = 'j. n. Y';

    /**
     * Datetime format for database
     */
    const DB_FORMAT = 'Y-m-d H:i:s';

    /**
     * Date format for database
     */
    const DB_FORMAT_DATE = 'Y-m-d';

    /**
     * Date format for version code etc     
     */
    const CODE_FORMAT = 'ymdhis';

    /**
     * Main DateTime value
     * @var \DateTime PHP core DateTime object 
     */
    private $date;

    public function __construct()
    {
        $this->date = null;
    }

    /**
     * Create date factory
     * @param string $format input date format
     * @param string $dateString date string
     * @return DateTime
     */
    public static function createFromFormat(string $format, string $dateString): DateTime
    {
        $object = new DateTime();
        $object->setDate(\DateTime::createFromFormat($format, $dateString));
        return $object;
    }

    /**
     * Create NOW date, default is DB_FORMAT 
     * @return DateTime
     */
    public static function createNow(): DateTime
    {
        return self::createFromFormat(self::DB_FORMAT, date('Y-m-d H:i:s'));
    }

    /**
     * Whole date setter
     * @param \DateTime|false $date
     * @return DateTime
     */
    public function setDate($date): DateTime
    {
        if ($date instanceof \DateTime) {
            $this->date = $date;
        }
        return $this;
    }

    /**
     * Date checker
     * @return bool
     */
    public function isDate(): bool
    {
        return $this->date instanceof \DateTime;
    }

    /**
     * Time setter
     * @param int $hour
     * @param int $minute
     * @param int $second
     * @return DateTime
     */
    public function setTime($hour = 0, $minute = 0, $second = 0): DateTime
    {
        if (
            $this->date instanceof \DateTime &&
            (is_numeric($hour) && ($hour >= 0 || $hour <= 23)) &&
            (is_numeric($minute) && ($minute >= 0 || $minute <= 59)) &&
            (is_numeric($second) && ($second >= 0 || $second <= 23))
        ) {
            $this->date->setTime($hour, $minute, $second);
        }
        return $this;
    }

    /**
     * Set time by time string
     * @param string $time
     * @return DateTime
     */
    public function setTimeFromString(string $time): DateTime
    {
        $timeArray = explode(':', $time);
        if (is_array($timeArray)) {
            $this->setTime(isset($timeArray[0]) ? intval($timeArray[0]) : 0, isset($timeArray[1]) ? intval($timeArray[1]) : 0, isset($timeArray[2]) ? intval($timeArray[2]) : 0);
        }
        return $this;
    }

    /**
     * Get Unix Time Stamp
     * @return int|false
     */
    public function getTimestamp()
    {
        return ($this->date instanceof \DateTime) ? $this->date->getTimestamp() : false;
    }

    /**
     * Adding days to date
     * @param int $days
     * @return Datetime
     */
    public function addDays(int $days = 1): Datetime
    {
        if ($this->date instanceof \DateTime) {
            $this->date->modify('+' . intval($days) . ' day');
        }
        return $this;
    }

    /**
     * Adding hours to date
     * @param int $hours
     * @return DateTime
     */
    public function addHours(int $hours = 1): DateTime
    {
        if ($this->date instanceof \DateTime) {
            $this->date->modify('+' . intval($hours) . ' hour');
        }
        return $this;
    }

    /**
     * Adding minutes to date
     * @param int $minutes
     * @return DateTime
     */
    public function addMinutes(int $minutes = 1): DateTime
    {
        if ($this->date instanceof \DateTime) {
            $this->date->modify('+' . intval($minutes) . ' minute');
        }
        return $this;
    }

    /**
     * Generate output string according the format
     * @param string $format the output format
     * @return string
     */
    public function format(string $format): string
    {
        if ($this->date instanceof \DateTime) {
            if ($format == self::HUMAN_FORMAT && $this->date->format("H:i") == '00:00') {
                $format = self::HUMAN_FORMAT_DATE;
            }
            return date($format, $this->getTimestamp());
        } else {
            return '';
        }
    }

    /**
     * MySQL database format
     * @return string 
     */
    public function toDatabase(): string
    {
        return $this->format(self::DB_FORMAT);
    }

    /**
     * Human date format
     * @return string
     */
    public function toHuman(): string
    {
        return $this->format(self::HUMAN_FORMAT);
    }

    /**
     * Convert the date from database format to human format
     * @param string $dateString
     * @param type $formatOut output format
     * @param type $formatIn input format
     * @return string
     */
    public static function stringToHuman(string $dateString, string $formatOut = self::HUMAN_FORMAT, string $formatIn = self::DB_FORMAT): string
    {
        $date = self::createFromFormat($formatIn, $dateString);
        return $date->format($formatOut);
    }

    /**
     * Convert the date from human format to database format
     * @param string $dateString
     * @param type $formatOut output format
     * @param type $formatIn input format
     * @return string
     */
    public static function stringToDatabase(string $dateString, string $formatOut = self::DB_FORMAT, string $formatIn = self::HUMAN_FORMAT): string
    {
        $date = self::createFromFormat($formatIn, $dateString);
        return $date->format($formatOut);
    }

    public function __clone()
    {
        $this->date = clone $this->date;
    }

    /**
     * Less then
     * If object is less then argument
     * @param DateTime $dateToCompare
     * @param bool $lessAndEqual
     * @return bool
     */
    public function lt(DateTime $dateToCompare, bool $lessAndEqual = true): bool
    {
        if ($lessAndEqual) {
            return $this->getTimestamp() <= $dateToCompare->getTimestamp();
        } else {
            return $this->getTimestamp() < $dateToCompare->getTimestamp();
        }
    }

    /**
     * Greater then
     * If object is greater then argument
     * @param DateTime $dateToCompare
     * @param bool $greaterAndEqual
     * @return bool
     */
    public function gt(DateTime $dateToCompare, bool $greaterAndEqual = true): bool
    {
        return !$this->lt($dateToCompare, !$greaterAndEqual);
    }

    /**
     *Equal
     * If object is the same like argument
     * @param DateTime $dateToCompare
     * @return bool
     */
    public function equal(DateTime $dateToCompare): bool
    {
        return $this->getTimestamp() == $dateToCompare->getTimestamp();
    }
}
