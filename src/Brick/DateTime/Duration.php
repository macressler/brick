<?php

namespace Brick\DateTime;

use Brick\Type\Cast;

/**
 * Represents a duration of time measured in seconds.
 *
 * This class is immutable.
 *
 * @todo format() method to output something like 3 hours, 4 minutes and 30 seconds
 */
class Duration
{
    /**
     * The duration in seconds.
     *
     * @var int
     */
    private $seconds;

    /**
     * Private constructor. Use one of the factory methods to obtain a Duration.
     *
     * @param int $seconds The duration in seconds, validated as an integer.
     */
    private function __construct($seconds)
    {
        $this->seconds = $seconds;
    }

    /**
     * Returns a zero length Duration.
     *
     * @return Duration
     */
    public static function zero()
    {
        return new Duration(0);
    }

    /**
     * Obtains an instance of `Duration` by parsing a text string.
     *
     * This will parse the string produced by `toString()` which is
     * the ISO-8601 format `PTnS` where `n` is the number of seconds.
     *
     * @param string $text
     * @return Duration
     * @throws Parser\DateTimeParseException
     */
    public static function parse($text)
    {
        if (preg_match('/^PT(\-?[0-9]+)S$/i', $text, $matches) == 0) {
            throw Parser\DateTimeParseException::invalidDuration($text);
        }

        try {
            return new Duration(Cast::toInteger($matches[1]));
        } catch (\InvalidArgumentException $e) {
            throw Parser\DateTimeParseException::invalidDuration($text);
        }
    }

    /**
     * Returns a Duration from a number of seconds.
     *
     * @param int $seconds
     * @return Duration
     */
    public static function ofSeconds($seconds)
    {
        return new Duration($seconds);
    }

    /**
     * Returns a Duration from a number of minutes.
     *
     * @param int $minutes
     * @return Duration
     */
    public static function ofMinutes($minutes)
    {
        return new Duration(60 * $minutes);
    }

    /**
     * Returns a Duration from a number of hours.
     *
     * @param int $hours
     * @return Duration
     */
    public static function ofHours($hours)
    {
        return new Duration(3600 * $hours);
    }

    /**
     * Returns a Duration from a number of days.
     *
     * @param int $days
     * @return Duration
     */
    public static function ofDays($days)
    {
        return new Duration(86400 * $days);
    }

    /**
     * Returns a Duration representing the time elapsed between two instants.
     *
     * A Duration represents a directed distance between two points on the time-line.
     * As such, this method will return a negative duration if the end is before the start.
     *
     * @param PointInTime $startInclusive The start instant, inclusive.
     * @param PointInTime $endExclusive   The end instant, exclusive.
     * @return Duration
     */
    public static function between(PointInTime $startInclusive, PointInTime $endExclusive)
    {
        return new Duration($endExclusive->getTimestamp() - $startInclusive->getTimestamp());
    }

    /**
     * Returns a Duration object with the given number of seconds, checking
     * to see if a new object is in fact required.
     *
     * @param int $seconds The duration in seconds, validated as an integer.
     * @return Duration
     */
    private function create($seconds)
    {
        if ($seconds == $this->seconds) {
            return $this;
        }

        return new Duration($seconds);
    }

    /**
     * Returns whether this Duration is zero length.
     *
     * @return bool
     */
    public function isZero()
    {
        return $this->seconds == 0;
    }

    /**
     * Returns whether this Duration is positive, excluding zero.
     *
     * @return bool
     */
    public function isPositive()
    {
        return $this->seconds > 0;
    }

    /**
     * Returns whether this Duration is positive, including zero.
     *
     * @return bool
     */
    public function isPositiveOrZero()
    {
        return $this->seconds >= 0;
    }

    /**
     * Returns whether this Duration is negative, excluding zero.
     *
     * @return bool
     */
    public function isNegative()
    {
        return $this->seconds < 0;
    }

    /**
     * Returns whether this Duration is negative, including zero.
     *
     * @return bool
     */
    public function isNegativeOrZero()
    {
        return $this->seconds <= 0;
    }

    /**
     * Returns a copy of this Duration with the specified duration added.
     *
     * @param Duration $duration
     * @return Duration
     */
    public function plus(Duration $duration)
    {
        return $this->create($this->seconds + $duration->seconds);
    }

    /**
     * Returns a copy of this Duration with the specified duration in seconds added.
     *
     * @param int $secondsToAdd
     * @return Duration
     */
    public function plusSeconds($secondsToAdd)
    {
        return $this->create($this->seconds + $secondsToAdd);
    }

    /**
     * Returns a copy of this Duration with the specified duration in minutes added.
     *
     * @param int $minutesToAdd
     * @return Duration
     */
    public function plusMinutes($minutesToAdd)
    {
        return $this->create($this->seconds + 60 * $minutesToAdd);
    }

    /**
     * Returns a copy of this Duration with the specified duration in hours added.
     *
     * @param int $hoursToAdd
     * @return Duration
     */
    public function plusHours($hoursToAdd)
    {
        return $this->create($this->seconds + 3600 * $hoursToAdd);
    }

    /**
     * Returns a copy of this Duration with the specified duration in days added.
     *
     * @param int $daysToAdd
     * @return Duration
     */
    public function plusDays($daysToAdd)
    {
        return $this->create($this->seconds + 86400 * $daysToAdd);
    }

    /**
     * Returns a copy of this Duration with the specified duration in seconds subtracted.
     *
     * @param int $secondsToSubtract
     * @return Duration
     */
    public function minusSeconds($secondsToSubtract)
    {
        return $this->plusSeconds(- $secondsToSubtract);
    }

    /**
     * Returns a copy of this Duration with the specified duration in minutes subtracted.
     *
     * @param int $minutesToSubtract
     * @return Duration
     */
    public function minusMinutes($minutesToSubtract)
    {
        return $this->plusMinutes(- $minutesToSubtract);
    }

    /**
     * Returns a copy of this Duration with the specified duration in hours subtracted.
     *
     * @param int $hoursToSubtract
     * @return Duration
     */
    public function minusHours($hoursToSubtract)
    {
        return $this->plusHours(- $hoursToSubtract);
    }

    /**
     * Returns a copy of this Duration with the specified duration in days subtracted.
     *
     * @param int $daysToSubtract
     * @return Duration
     */
    public function minusDays($daysToSubtract)
    {
        return $this->plusDays(- $daysToSubtract);
    }

    /**
     * Returns a copy of this Duration multiplied by the specified value.
     *
     * @param int $multiplicand
     * @return Duration
     */
    public function multipliedBy($multiplicand)
    {
        return $this->create($this->seconds * $multiplicand);
    }

    /**
     * Returns a copy of this Duration with the length negated.
     *
     * @return Duration
     */
    public function negated()
    {
        return $this->create(- $this->seconds);
    }

    /**
     * Returns a copy of this Duration with a positive length.
     *
     * @return Duration
     */
    public function abs()
    {
        return $this->isNegative() ? $this->negated() : $this;
    }

    /**
     * Compares this Duration to the specified duration.
     *
     * @param Duration $other The other duration to compare to.
     * @return int The comparator value, negative if less, positive if greater.
     */
    public function compareTo(Duration $other)
    {
        return $this->seconds - $other->seconds;
    }

    /**
     * Checks if this Duration is equal to the specified duration.
     *
     * @param Duration $other
     * @return bool
     */
    public function isEqualTo(Duration $other)
    {
        return $this->compareTo($other) == 0;
    }

    /**
     * Checks if this Duration is greater than the specified duration.
     *
     * @param Duration $other
     * @return bool
     */
    public function isGreaterThan(Duration $other)
    {
        return $this->compareTo($other) > 0;
    }

    /**
     * Checks if this Duration is greater, or equal to than the specified duration.
     *
     * @param Duration $other
     * @return bool
     */
    public function isGreaterThanOrEqualTo(Duration $other)
    {
        return $this->compareTo($other) >= 0;
    }

    /**
     * Checks if this Duration is less than the specified duration.
     *
     * @param Duration $other
     * @return bool
     */
    public function isLessThan(Duration $other)
    {
        return $this->compareTo($other) < 0;
    }

    /**
     * Checks if this Duration is less than, or equal to the specified duration.
     *
     * @param Duration $other
     * @return bool
     */
    public function isLessThanOrEqualTo(Duration $other)
    {
        return $this->compareTo($other) <= 0;
    }

    /**
     * Returns the total length in seconds of this Duration.
     *
     * @return int
     */
    public function getSeconds()
    {
        return $this->seconds;
    }

    /**
     * Returns a string representation of this duration using ISO-8601 seconds
     * based representation, such as `PT12S`.
     *
     * @return string
     */
    public function toString()
    {
        return 'PT' . $this->seconds . 'S';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}