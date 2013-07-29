<?php

namespace Brick\Tests\Money;

use Brick\Money\Money;
use Brick\Locale\Currency;
use Brick\Math\Decimal;

/**
 * Unit test for class Money
 */
class MoneyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Brick\Money\Money
     */
    private $money;

    /**
     * @var \Brick\Locale\Currency
     */
    private $currency;

    public function setUp()
    {
        $this->currency = Currency::getInstance('EUR');
        $this->money = new Money($this->currency, Decimal::fromInteger(10));
    }

    public function testPlus()
    {
        $newMoney = new Money($this->currency, Decimal::fromString('5.50'));

        $this->assertTrue($this->money->isGreaterThanOrEqualTo($newMoney));

        $this->money = $this->money->plus($newMoney);
        $this->assertEquals($this->money->getAmount()->toString(), '15.50');
    }

    public function testMinus()
    {
        $newMoney = new Money($this->currency, Decimal::fromString('5.50'));

        $this->money = $this->money->minus($newMoney);
        $this->assertEquals($this->money->getAmount()->toString(), '4.50');
    }

    public function testTimes()
    {
        $this->money = $this->money->times(5);
        $this->assertEquals($this->money->getAmount()->toString(), '50.00');
    }

    public function testAdjustments()
    {
        $this->assertFalse($this->money->isZero());
        $this->assertFalse($this->money->isNegative());

        $newMoney = new Money($this->currency, Decimal::fromString('10'));

        $this->money = $this->money->minus($newMoney);
        $this->assertTrue($this->money->isZero());

        $this->money = $this->money->minus($newMoney);
        $this->assertTrue($this->money->isNegative());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDifferentCurrenciesThrowException()
    {
        $eur = new Money(Currency::getInstance('EUR'), Decimal::fromString('1'));
        $usd = new Money(Currency::getInstance('USD'), Decimal::fromString('1'));

        $eur->plus($usd);
    }
}