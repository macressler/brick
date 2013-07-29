<?php

namespace Brick\Html;

/**
 * Represents an HTML tag.
 */
abstract class Tag
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $attributes = array();

    /**
     * Class constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasAttribute($name)
    {
        return isset($this->attributes[$name]);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getAttribute($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @param string $name
     */
    public function removeAttribute($name)
    {
        unset($this->attributes[$name]);
    }

    /**
     * @return string
     */
    protected function renderAttributes()
    {
        $result = '';

        foreach ($this->attributes as $name => $value) {
            $result .= sprintf(' %s="%s"', $name, htmlspecialchars($value));
        }

        return $result;
    }

    /**
     * @return string
     */
    abstract public function render();

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}