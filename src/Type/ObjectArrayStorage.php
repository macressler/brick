<?php

namespace Brick\Type;

/**
 * Associates an array of values to an object.
 */
class ObjectArrayStorage implements \Countable, \IteratorAggregate
{
    /**
     * The underlying storage.
     *
     * @var ObjectStorage
     */
    private $storage;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->storage = new ObjectStorage();
    }

    /**
     * Returns whether this storage contains the given object.
     *
     * @param object $object The object to test.
     *
     * @return boolean True if this storage contains the object, false otherwise.
     */
    public function has($object)
    {
        return $this->storage->has($object);
    }

    /**
     * Returns the values associated to the given object.
     *
     * If the object is not present in the storage, an empty array is returned.
     *
     * @param object $object The object.
     *
     * @return array The values associated with the object.
     */
    public function get($object)
    {
        $values = $this->storage->get($object);

        return ($values === null) ? [] : $values;
    }

    /**
     * Adds data associated with the given object.
     *
     * @param object $object The object.
     * @param mixed  $value  The value to add.
     *
     * @return static This ObjectArrayStorage instance.
     */
    public function add($object, $value)
    {
        $values = $this->get($object);
        $values[] = $value;
        $this->storage->set($object, $values);

        return $this;
    }

    /**
     * Removes all values associated with the given object from the storage.
     *
     * If this storage does not any value for the given object, this method does nothing.
     *
     * @param object $object The object to remove.
     *
     * @return static This ObjectStorage instance.
     */
    public function remove($object)
    {
        $this->storage->remove($object);

        return $this;
    }

    /**
     * Returns the number of objects in this storage.
     *
     * This method is part of the Countable interface.
     *
     * @return integer
     */
    public function count()
    {
        return $this->storage->count();
    }

    /**
     * Returns an iterator for this storage.
     *
     * This method is part of the IteratorAggregate interface.
     *
     * @return \Generator
     */
    public function getIterator()
    {
        return $this->storage->getIterator();
    }
}
