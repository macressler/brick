<?php

namespace Brick\Money;

use Brick\Locale\Currency;
use Brick\Math\Decimal;
use Brick\Locale\Locale;
use NumberFormatter;

/**
 * Class for handling exact calculations with monies.
 */
class Money
{
    /**
     * @var \Brick\Locale\Currency
     */
    private $currency;

    /**
     * @var \Brick\Math\Decimal
     */
    private $amount;

    /**
     * @param \Brick\Locale\Currency $currency
     * @param \Brick\Math\Decimal               $amount
     */
    public function __construct(Currency $currency, Decimal $amount)
    {
        $this->currency = $currency;
        $scale = $currency->getDefaultFractionDigits();
        $this->amount = $amount->setScale($scale);
    }

    /**
     * @param  \Brick\Locale\Currency $currency
     * @param  string                             $amount
     * @return Money
     */
    public static function parse(Currency $currency, $amount)
    {
        $amount = Decimal::fromString($amount);

        return new self($currency, $amount);
    }

    /**
     * Returns a Money with zero value, in the given Currency.
     *
     * @param  \Brick\Locale\Currency $currency
     * @return Money
     */
    public static function zero(Currency $currency)
    {
        return new self($currency, Decimal::zero());
    }

    /**
     * Returns the Currency of this Money.
     *
     * @return \Brick\Locale\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Returns the amount of this Money, as a Decimal.
     *
     * @return \Brick\Math\Decimal
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param Money $money
     * @return void
     * @throws \RuntimeException
     */
    protected function checkCurrenciesMatch(Money $money)
    {
        if (! $money->getCurrency()->isEqualTo($this->currency)) {
            throw new \RuntimeException(sprintf('Currencies mismatch: %s != %s',
                    $money->getCurrency()->getCurrencyCode(),
                    $this->currency->getCurrencyCode())
            );
        }
    }

    /**
     * @param  Money $money
     * @return Money
     */
    public function plus(Money $money)
    {
        $this->checkCurrenciesMatch($money);

        return new Money($this->currency, $this->amount->add($money->getAmount()));
    }

    /**
     * @param  Money $money
     * @return Money
     */
    public function minus(Money $money)
    {
        $this->checkCurrenciesMatch($money);

        return new Money($this->currency, $this->amount->subtract($money->getAmount()));
    }

    /**
     * @param  integer $value
     * @return Money
     */
    public function times($value)
    {
        $value = Decimal::fromInteger($value);

        return new Money($this->currency, $this->amount->multiply($value));
    }

    /**
     * Returns whether this Money has zero value.
     *
     * @return boolean
     */
    public function isZero()
    {
        return $this->amount->isZero();
    }

    /**
     * Returns whether this Money has a negative value.
     *
     * @return boolean
     */
    public function isNegative()
    {
        return $this->amount->isNegative();
    }

    /**
     * Returns whether this Money equals another Money.
     *
     * @param  Money   $money
     * @return boolean
     */
    public function isEqualTo(Money $money)
    {
        $this->checkCurrenciesMatch($money);

        return $this->amount->isEqualTo($money->getAmount());
    }

    /**
     * @param  Money   $money
     * @return boolean
     */
    public function isGreaterThanOrEqualTo(Money $money)
    {
        $this->checkCurrenciesMatch($money);

        return $this->amount->isGreaterThanOrEqualTo($money->getAmount());
    }

    /**
     * Returns a formatted string, according to the given Locale.
     * Note: even if all calculations are done with exact arithmetic,
     * the NumberFormatter here accepts a floating-point value only.
     * This should not be an issue for formatting though, as the output has been
     * verified to be the correct one on the range [-1000000.00,1000000.00]
     *
     * @param  Locale $locale
     * @return string
     */
    public function format(Locale $locale)
    {
        $formatter = new NumberFormatter($locale->toString(), NumberFormatter::CURRENCY);
        $formatter->setSymbol(NumberFormatter::CURRENCY_SYMBOL, $this->currency->getSymbol());

        return $formatter->format((float) $this->amount->toString());
    }

    /**
     * Returns a non-localized string representation of this Money
     * e.g. "EUR 25.00"
     *
     * @return string
     */
    public function toString()
    {
        return $this->currency->getCurrencyCode() . ' ' . $this->amount->toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}