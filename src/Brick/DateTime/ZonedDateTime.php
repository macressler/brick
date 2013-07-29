<?php

namespace Brick\DateTime;

use Brick\Locale\Locale;

/**
 * A date-time with a time-zone in the ISO-8601 calendar system.
 *
 * A ZonedDateTime can be viewed as a LocalDateTime along with a time zone
 * and targets a specific point in time.
 */
class ZonedDateTime extends PointInTime
{
    /**
     * The local date-time.
     *
     * @var LocalDateTime
     */
    private $localDateTime;

    /**
     * The time-zone.
     *
     * @var TimeZone
     */
    private $timeZone;

    /**
     * A native DateTime object to perform some of the calculations.
     *
     * @var \DateTime
     */
    private $dateTime;

    /**
     * Private constructor. Use of one the factory methods to obtain a ZonedDateTime.
     *
     * @param LocalDateTime $localDateTime
     * @param TimeZone      $timeZone
     * @param \DateTime     $dateTime
     */
    private function __construct(LocalDateTime $localDateTime, TimeZone $timeZone, \DateTime $dateTime)
    {
        $this->localDateTime = $localDateTime;
        $this->timeZone = $timeZone;
        $this->dateTime = $dateTime;
    }

    /**
     * @param LocalDateTime       $localDateTime
     * @param TimeZone            $timeZone
     * @param TimeZoneOffset|null $preferredOffset
     * @return ZonedDateTime
     *
     * @todo preferredOffset
     */
    public static function of(LocalDateTime $localDateTime, TimeZone $timeZone, TimeZoneOffset $preferredOffset = null)
    {
        $dateTime = new \DateTime($localDateTime->toString(), $timeZone->toDateTimeZone());

        return new ZonedDateTime($localDateTime, $timeZone, $dateTime);
    }

    /**
     * Creates a DateTime representing the current time, in the given time zone.
     *
     * @param TimeZone $timeZone
     * @return ZonedDateTime
     */
    public static function now(TimeZone $timeZone)
    {
        return Instant::now()->toZonedDateTime($timeZone);
    }

    /**
     * Obtains an instance of `ZonedDateTime` from a set of date-time fields.
     *
     * This method is only useful to parsers.
     *
     * @param Field\DateTimeFieldSet $fieldSet
     * @return ZonedDateTime
     */
    public static function from(Field\DateTimeFieldSet $fieldSet)
    {
        return ZonedDateTime::of(
            LocalDateTime::from($fieldSet),
            TimeZone::from($fieldSet)
        );
    }

    /**
     * Obtains an instance of `ZonedDateTime` from a text string such as `2007-12-03T10:15:30+01:00[Europe/Paris]`.
     *
     * @param string $text The text to parse, such as `2007-12-03T10:15:30+01:00[Europe/Paris]`.
     * @return ZonedDateTime
     * @throws DateTimeException
     */
    public static function parse($text)
    {
        // @todo temporary fix for UTC only
        if (substr($text, -1) == 'Z') {
            return LocalDateTime::parse(substr($text, 0, -1))->atTimeZone(TimeZone::utc());
        }

        $localDateTime = LocalDateTime::parse(substr($text, 0, -6));
        $timeZone = TimeZone::of(substr($text, -6));

        return self::of($localDateTime, $timeZone);

        // @todo

        $parser = Parser\DateTimeParsers::isoZonedDateTime();

        return ZonedDateTime::from($parser->parse($text));
    }

    /**
     * Creates a ZonedDateTime from an instant and a time zone.
     *
     * @param Instant  $instant  The instant.
     * @param TimeZone $timeZone The time zone.
     * @return ZonedDateTime
     */
    public static function ofInstant(Instant $instant, TimeZone $timeZone)
    {
        return ZonedDateTime::ofTimestamp($instant->getTimestamp(), $timeZone);
    }

    /**
     * Creates a ZonedDateTime from a timestamp and a time zone.
     *
     * @param int $timestamp
     * @param TimeZone $timeZone
     * @return ZonedDateTime
     */
    public static function ofTimestamp($timestamp, TimeZone $timeZone)
    {
        $dateTimeZone = $timeZone->toDateTimeZone();

        // We need to pass a DateTimeZone to avoid a PHP warning...
        $dateTime = new \DateTime('@' . $timestamp, $dateTimeZone);

        // ... but this DateTimeZone is ignored because of the timestamp, so we set it again.
        $dateTime->setTimezone($dateTimeZone);

        $localDateTime = LocalDateTime::parse($dateTime->format('Y-m-d\TH:i:s'));

        return new ZonedDateTime($localDateTime, $timeZone, $dateTime);
    }

    /**
     * Creates a ZonedDateTime on the specific date & time zone, at midnight.
     *
     * @param LocalDate $date    The date.
     * @param TimeZone $timeZone The time zone.
     * @return ZonedDateTime
     */
    public static function createFromDate(LocalDate $date, TimeZone $timeZone)
    {
        return ZonedDateTime::createFromDateAndTime($date, LocalTime::midnight(), $timeZone);
    }

    /**
     * Creates a ZonedDateTime on the specific date, time & time zone.
     *
     * @param LocalDate $date     The date.
     * @param LocalTime $time     The time.
     * @param TimeZone  $timeZone The time zone.
     * @return ZonedDateTime
     *
     * @todo fromLocalDateTime() ? fromLocal() ? ofLocal() ? of()?
     */
    public static function createFromDateAndTime(LocalDate $date, LocalTime $time, TimeZone $timeZone)
    {
        return ZonedDateTime::of(LocalDateTime::ofDateTime($date, $time), $timeZone);
    }

    /**
     * Returns the `LocalDateTime` part of this `ZonedDateTime`.
     *
     * @return LocalDateTime
     */
    public function getDateTime()
    {
        return $this->localDateTime;
    }

    /**
     * Returns the `LocalDate` part of this `ZonedDateTime`.
     *
     * @return LocalDate
     */
    public function getDate()
    {
        return $this->localDateTime->getDate();
    }

    /**
     * Returns the `LocalTime` part of this `ZonedDateTime`.
     *
     * @return LocalTime
     */
    public function getTime()
    {
        return $this->localDateTime->getTime();
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->localDateTime->getYear();
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->localDateTime->getMonth();
    }

    /**
     * @return int
     */
    public function getDay()
    {
        return $this->localDateTime->getDay();
    }

    /**
     * @return Weekday
     */
    public function getDayOfWeek()
    {
        return $this->localDateTime->getDayOfWeek();
    }

    /**
     * @return int
     */
    public function getHour()
    {
        return $this->localDateTime->getHour();
    }

    /**
     * @return int
     */
    public function getMinute()
    {
        return $this->localDateTime->getMinute();
    }

    /**
     * @return int
     */
    public function getSecond()
    {
        return $this->localDateTime->getSecond();
    }

    /**
     * Returns the time-zone, such as `Europe/Paris`.
     *
     * @return TimeZone
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * Returns the time zone offset, such as `+01:00`.
     *
     * @return TimeZoneOffset
     */
    public function getOffset()
    {
        if ($this->timeZone instanceof TimeZoneOffset) {
            return $this->timeZone;
        }

        $dateTimeZone = $this->timeZone->toDateTimeZone();
        $offset = $dateTimeZone->getOffset($this->dateTime);

        return TimeZoneOffset::ofTotalSeconds($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function getTimestamp()
    {
        return $this->dateTime->getTimestamp();
    }

    /**
     * Returns a copy of this ZonedDateTime with a different date.
     *
     * @param LocalDate $date
     * @return ZonedDateTime
     */
    public function withDate(LocalDate $date)
    {
        return ZonedDateTime::of($this->localDateTime->withDate($date), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with a different time.
     *
     * @param LocalTime $time
     * @return ZonedDateTime
     */
    public function withTime(LocalTime $time)
    {
        return ZonedDateTime::of($this->localDateTime->withTime($time), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with the year altered.
     *
     * @param int $year
     * @return ZonedDateTime
     */
    public function withYear($year)
    {
        return ZonedDateTime::of($this->localDateTime->withYear($year), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with the month-of-year altered.
     *
     * @param int $month
     * @return ZonedDateTime
     */
    public function withMonth($month)
    {
        return ZonedDateTime::of($this->localDateTime->withMonth($month), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with the day-of-month altered.
     *
     * @param int $day
     * @return ZonedDateTime
     */
    public function withDay($day)
    {
        return ZonedDateTime::of($this->localDateTime->withDay($day), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with the hour-of-day altered.
     *
     * @param int $hour
     * @return ZonedDateTime
     */
    public function withHour($hour)
    {
        return ZonedDateTime::of($this->localDateTime->withHour($hour), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with the minute-of-hour altered.
     *
     * @param int $minute
     * @return ZonedDateTime
     */
    public function withMinute($minute)
    {
        return ZonedDateTime::of($this->localDateTime->withMinute($minute), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with the second-of-minute altered.
     *
     * @param int $second
     * @return ZonedDateTime
     */
    public function withSecond($second)
    {
        return ZonedDateTime::of($this->localDateTime->withSecond($second), $this->timeZone);
    }

    /**
     * Returns a copy of this `ZonedDateTime` with a different time-zone,
     * retaining the local date-time if possible.
     *
     * @param TimeZone $timeZone The time-zone to change to.
     * @return ZonedDateTime
     */
    public function withTimeZoneSameLocal(TimeZone $timeZone)
    {
        return ZonedDateTime::of($this->localDateTime, $timeZone);
    }

    /**
     * Returns a copy of this date-time with a different time-zone,
     * retaining the instant.
     *
     * @param TimeZone $timeZone
     * @return ZonedDateTime
     */
    public function withTimeZoneSameInstant(TimeZone $timeZone)
    {
        return ZonedDateTime::ofInstant($this->toInstant(), $timeZone);
    }

    /**
     * Returns a copy of this date-time with the time-zone set to the offset.
     *
     * This returns a zoned date-time where the time-zone is the same as `getOffset()`.
     * The local date-time, offset and instant of the result will be the same as in this date-time.
     *
     * Setting the date-time to a fixed single offset means that any future
     * calculations, such as addition or subtraction, have no complex edge cases
     * due to time-zone rules.
     * This might also be useful when sending a zoned date-time across a network,
     * as most protocols, such as ISO-8601, only handle offsets, and not region-based time zones.
     *
     * @return ZonedDateTime
     */
    public function withFixedOffsetTimeZone()
    {
        return ZonedDateTime::of($this->localDateTime, $this->getOffset());
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified Period added.
     *
     * @param Period $period
     * @return ZonedDateTime
     */
    public function plusPeriod(Period $period)
    {
        return ZonedDateTime::of($this->localDateTime->plusPeriod($period), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified Duration added.
     *
     * @param Duration $duration
     * @return ZonedDateTime
     */
    public function plusDuration(Duration $duration)
    {
        return ZonedDateTime::ofInstant($this->toInstant()->plus($duration), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified period in years added.
     *
     * @param int $years
     * @return ZonedDateTime
     */
    public function plusYears($years)
    {
        return ZonedDateTime::of($this->localDateTime->plusYears($years), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified period in months added.
     *
     * @param int $months
     * @return ZonedDateTime
     */
    public function plusMonths($months)
    {
        return ZonedDateTime::of($this->localDateTime->plusMonths($months), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified period in weeks added.
     *
     * @param int $weeks
     * @return ZonedDateTime
     */
    public function plusWeeks($weeks)
    {
        return ZonedDateTime::of($this->localDateTime->plusWeeks($weeks), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified period in days added.
     *
     * @param int $days
     * @return ZonedDateTime
     */
    public function plusDays($days)
    {
        return ZonedDateTime::of($this->localDateTime->plusDays($days), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified period in hours added.
     *
     * @param int $hours
     * @return ZonedDateTime
     */
    public function plusHours($hours)
    {
        return ZonedDateTime::of($this->localDateTime->plusHours($hours), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified period in minutes added.
     *
     * @param int $minutes
     * @return ZonedDateTime
     */
    public function plusMinutes($minutes)
    {
        return ZonedDateTime::of($this->localDateTime->plusMinutes($minutes), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified period in seconds added.
     *
     * @param int $seconds
     * @return ZonedDateTime
     */
    public function plusSeconds($seconds)
    {
        return ZonedDateTime::of($this->localDateTime->plusSeconds($seconds), $this->timeZone);
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified Period subtracted.
     *
     * @param Period $period
     * @return ZonedDateTime
     */
    public function minusPeriod(Period $period)
    {
        return $this->plusPeriod($period->negated());
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified Duration subtracted.
     *
     * @param Duration $duration
     * @return ZonedDateTime
     */
    public function minusDuration(Duration $duration)
    {
        return $this->plusDuration($duration->negated());
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified period in years subtracted.
     *
     * @param int $years
     * @return ZonedDateTime
     */
    public function minusYears($years)
    {
        return $this->plusYears(- $years);
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified period in months subtracted.
     *
     * @param int $months
     * @return ZonedDateTime
     */
    public function minusMonths($months)
    {
        return $this->plusMonths(- $months);
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified period in weeks subtracted.
     *
     * @param int $weeks
     * @return ZonedDateTime
     */
    public function minusWeeks($weeks)
    {
        return $this->plusWeeks(- $weeks);
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified period in days subtracted.
     *
     * @param int $days
     * @return ZonedDateTime
     */
    public function minusDays($days)
    {
        return $this->plusDays(- $days);
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified period in hours subtracted.
     *
     * @param int $hours
     * @return ZonedDateTime
     */
    public function minusHours($hours)
    {
        return $this->plusHours(- $hours);
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified period in minutes subtracted.
     *
     * @param int $minutes
     * @return ZonedDateTime
     */
    public function minusMinutes($minutes)
    {
        return $this->plusMinutes(- $minutes);
    }

    /**
     * Returns a copy of this ZonedDateTime with the specified period in seconds subtracted.
     *
     * @param int $seconds
     * @return ZonedDateTime
     */
    public function minusSeconds($seconds)
    {
        return $this->plusSeconds(- $seconds);
    }

    /**
     * Returns the instant represented by this ZonedDateTime.
     *
     * @return Instant
     */
    public function toInstant()
    {
        return Instant::of($this->getTimestamp());
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->localDateTime->toString() . $this->getOffset()->getId();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Returns a ZonedDateTime representing the same date, time and time zone as the given native DateTime object.
     *
     * @param \DateTime $dateTime
     * @return ZonedDateTime
     */
    public static function fromDateTime(\DateTime $dateTime)
    {
        $timeZone = TimeZone::fromDateTimeZone($dateTime->getTimezone());
        $localDateTime = LocalDateTime::parse($dateTime->format('Y-m-d\TH:i:s'));

        return new ZonedDateTime($localDateTime, $timeZone, clone $dateTime);
    }

    /**
     * Returns a native DateTime object representing the same date, time and time zone as this date-time.
     *
     * @return \DateTime
     */
    public function toDateTime()
    {
        return clone $this->dateTime;
    }

    /**
     * @param \Brick\Locale\Locale $locale
     * @return string
     */
    public function format(Locale $locale)
    {
        return $this->getDateTime()->format($locale);
    }
}