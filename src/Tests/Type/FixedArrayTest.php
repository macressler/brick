<?php

namespace Brick\Tests\Type;

use Brick\Type\FixedArray;

/**
 * Unit tests for class FixedArray.
 */
class FixedArrayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerFromArray
     *
     * @param array   $source      The source array.
     * @param boolean $saveIndexes Whether to use the save indexes functionality.
     * @param array   $expected    The expected result array.
     */
    public function testFromArray(array $source, $saveIndexes, array $expected)
    {
        $fixedArray = FixedArray::fromArray($source, $saveIndexes);

        $this->assertInstanceOf(FixedArray::class, $fixedArray);
        $this->assertSame($expected, iterator_to_array($fixedArray));
    }

    /**
     * @return array
     */
    public function providerFromArray()
    {
        return [
            [['2' => 'x', 4 => 'y'], false, ['x', 'y']],
            [[2 => 'x', '4' => 'y'], true, [null, null, 'x', null, 'y']]
        ];
    }

    /**
     * @dataProvider providerFromInvalidArrayThrowsException
     * @expectedException \InvalidArgumentException
     *
     * @param array   $source      The source array.
     * @param boolean $saveIndexes Whether to use the save indexes functionality.
     */
    public function testFromInvalidArrayThrowsException(array $source, $saveIndexes)
    {
        FixedArray::fromArray($source);
    }

    /**
     * @return array
     */
    public function providerFromInvalidArrayThrowsException()
    {
        return [
            [['x' => 'y'], false],
            [['x' => 'y'], true],
            [[-1 => 'z'], false],
            [[-1 => 'z'], true]
        ];
    }

    /**
     * @dataProvider providerSwap
     *
     * @param array   $source   The source array.
     * @param integer $index1   The index of the first entry.
     * @param integer $index2   The index of the second entry.
     * @param array   $expected The expected result array.
     */
    public function testSwap(array $source, $index1, $index2, array $expected)
    {
        $fixedArray = FixedArray::fromArray($source);

        $this->assertSame($fixedArray, $fixedArray->swap($index1, $index2));
        $this->assertSame($expected, iterator_to_array($fixedArray));
    }

    /**
     * @return array
     */
    public function providerSwap()
    {
        return [
            [['a', 'b', 'c'], 0, 0, ['a', 'b', 'c']],
            [['a', 'b', 'c'], 0, 1, ['b', 'a', 'c']],
            [['a', 'b', 'c'], 0, 2, ['c', 'b', 'a']],
            [['a', 'b', 'c'], 1, 0, ['b', 'a', 'c']],
            [['a', 'b', 'c'], 1, 1, ['a', 'b', 'c']],
            [['a', 'b', 'c'], 1, 2, ['a', 'c', 'b']],
            [['a', 'b', 'c'], 2, 0, ['c', 'b', 'a']],
            [['a', 'b', 'c'], 2, 1, ['a', 'c', 'b']],
            [['a', 'b', 'c'], 2, 2, ['a', 'b', 'c']]
        ];
    }

    /**
     * @dataProvider providerShiftUp
     *
     * @param array   $source   The source array.
     * @param integer $index    The index of the entry to shift.
     * @param array   $expected The expected result array.
     */
    public function testShiftUp(array $source, $index, array $expected)
    {
        $fixedArray = FixedArray::fromArray($source);

        $this->assertSame($fixedArray, $fixedArray->shiftUp($index));
        $this->assertSame($expected, iterator_to_array($fixedArray));
    }

    /**
     * @return array
     */
    public function providerShiftUp()
    {
        return [
            [['a', 'b', 'c', 'd', 'e'], 0, ['b', 'a', 'c', 'd', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 1, ['a', 'c', 'b', 'd', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 2, ['a', 'b', 'd', 'c', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 3, ['a', 'b', 'c', 'e', 'd']],
            [['a', 'b', 'c', 'd', 'e'], 4, ['a', 'b', 'c', 'd', 'e']],
        ];
    }

    /**
     * @dataProvider providerShiftDown
     *
     * @param array   $source   The source array.
     * @param integer $index    The index of the entry to shift.
     * @param array   $expected The expected result array.
     */
    public function testShiftDown(array $source, $index, array $expected)
    {
        $fixedArray = FixedArray::fromArray($source);

        $this->assertSame($fixedArray, $fixedArray->shiftDown($index));
        $this->assertSame($expected, iterator_to_array($fixedArray));
    }

    /**
     * @return array
     */
    public function providerShiftDown()
    {
        return [
            [['a', 'b', 'c', 'd', 'e'], 0, ['a', 'b', 'c', 'd', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 1, ['b', 'a', 'c', 'd', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 2, ['a', 'c', 'b', 'd', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 3, ['a', 'b', 'd', 'c', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 4, ['a', 'b', 'c', 'e', 'd']],
        ];
    }

    /**
     * @dataProvider providerShiftTo
     *
     * @param array   $source   The source array.
     * @param integer $index    The index of the entry.
     * @param integer $newIndex The index to shift the entry to.
     * @param array   $expected The expected result array.
     */
    public function testShiftTo(array $source, $index, $newIndex, array $expected)
    {
        $fixedArray = FixedArray::fromArray($source);

        $this->assertSame($fixedArray, $fixedArray->shiftTo($index, $newIndex));
        $this->assertSame($expected, iterator_to_array($fixedArray));
    }

    /**
     * @return array
     */
    public function providerShiftTo()
    {
        return [
            [['a', 'b', 'c', 'd', 'e'], 0, 0, ['a', 'b', 'c', 'd', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 0, 1, ['b', 'a', 'c', 'd', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 0, 2, ['b', 'c', 'a', 'd', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 0, 3, ['b', 'c', 'd', 'a', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 0, 4, ['b', 'c', 'd', 'e', 'a']],
            [['a', 'b', 'c', 'd', 'e'], 1, 0, ['b', 'a', 'c', 'd', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 1, 1, ['a', 'b', 'c', 'd', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 1, 2, ['a', 'c', 'b', 'd', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 1, 3, ['a', 'c', 'd', 'b', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 1, 4, ['a', 'c', 'd', 'e', 'b']],
            [['a', 'b', 'c', 'd', 'e'], 2, 0, ['c', 'a', 'b', 'd', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 2, 1, ['a', 'c', 'b', 'd', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 2, 2, ['a', 'b', 'c', 'd', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 2, 3, ['a', 'b', 'd', 'c', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 2, 4, ['a', 'b', 'd', 'e', 'c']],
            [['a', 'b', 'c', 'd', 'e'], 3, 0, ['d', 'a', 'b', 'c', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 3, 1, ['a', 'd', 'b', 'c', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 3, 2, ['a', 'b', 'd', 'c', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 3, 3, ['a', 'b', 'c', 'd', 'e']],
            [['a', 'b', 'c', 'd', 'e'], 3, 4, ['a', 'b', 'c', 'e', 'd']],
            [['a', 'b', 'c', 'd', 'e'], 4, 0, ['e', 'a', 'b', 'c', 'd']],
            [['a', 'b', 'c', 'd', 'e'], 4, 1, ['a', 'e', 'b', 'c', 'd']],
            [['a', 'b', 'c', 'd', 'e'], 4, 2, ['a', 'b', 'e', 'c', 'd']],
            [['a', 'b', 'c', 'd', 'e'], 4, 3, ['a', 'b', 'c', 'e', 'd']],
            [['a', 'b', 'c', 'd', 'e'], 4, 4, ['a', 'b', 'c', 'd', 'e']],
        ];
    }
}
