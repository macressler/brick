<?php

namespace Brick\Iterator;

/**
 * Iterator to read CSV files.
 */
class CsvFileIterator implements \Iterator
{
    /**
     * The file pointer resource.
     *
     * @var resource
     */
    private $handle;

    /**
     * The field delimiter (one character only).
     *
     * @var string
     */
    private $delimiter;

    /**
     * The field enclosure character (one character only).
     *
     * @var string
     */
    private $enclosure;

    /**
     * The escape character (one character only).
     *
     * @var string
     */
    private $escape;

    /**
     * The key of the current element (0-based).
     *
     * @var int
     */
    private $key;

    /**
     * The current element as a 0-indexed array, or null if end of file / error.
     *
     * @var array|null
     */
    private $current;

    /**
     * @var boolean
     */
    private $headerRow;

    /**
     * The column names, or null if returning numeric arrays.
     *
     * @var array|null
     */
    private $columns;

    /**
     * Class constructor.
     *
     * @param string|resource $file      The CSV file path, or an open file pointer.
     * @param boolean         $headerRow Whether the first row contains the column names.
     * @param string          $delimiter The field delimiter character.
     * @param string          $enclosure The field enclosure character.
     * @param string          $escape    The escape character.
     *
     * @throws \InvalidArgumentException If the file cannot be opened.
     */
    public function __construct($file, $headerRow = false, $delimiter = ',', $enclosure = '"', $escape = '\\')
    {
        if (is_resource($file)) {
            $this->handle = $file;
        } else {
            $this->handle = @ fopen($file, 'r');

            if ($this->handle === false) {
                throw new \InvalidArgumentException('Cannot open file for reading: ' . $file);
            }
        }

        $this->headerRow = $headerRow;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape    = $escape;

        $this->init();
    }

    /**
     * @return void
     */
    private function init()
    {
        if ($this->headerRow) {
            $this->columns = $this->readRow();

            if ($this->columns === null) {
                $this->columns = [];
            }
        }

        $this->readCurrent();
        $this->key = 0;
    }

    /**
     * Reads the current row.
     *
     * If EOF is reached or an error occurs, NULL is returned.
     * If the line is empty, an empty array is returned.
     *
     * @return array|null
     */
    private function readRow()
    {
        $row = @ fgetcsv($this->handle, 0, $this->delimiter, $this->enclosure, $this->escape);

        if ($row === false || $row === null) {
            return null;
        }

        if ($row[0] === null) {
            return [];
        }

        return $row;
    }

    /**
     * Reads the current CSV row.
     *
     * @return void
     */
    private function readCurrent()
    {
        $row = $this->readRow();

        if ($this->columns === null || $row === null) {
            $this->current = $row;
        } else {
            $this->current = [];

            foreach ($this->columns as $key => $name) {
                $this->current[$name] = isset($row[$key]) ? $row[$key] : null;
            }
        }
    }

    /**
     * Rewinds the Iterator to the first element.
     *
     * If the stream does not support seeking, the iterator will be left in the current position.
     *
     * @return void
     */
    public function rewind()
    {
        if ($this->key !== 0 && fseek($this->handle, 0) === 0) {
            $this->init();
        }
    }

    /**
     * Returns whether the current position is valid.
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->current !== null;
    }

    /**
     * Returns the key of the current element (0-based).
     *
     * @return integer
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Returns the current element, or null if end of file / error.
     *
     * @return array|null
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Move forward to next element.
     *
     * @return void
     */
    public function next()
    {
        $this->readCurrent();
        $this->key++;
    }
}
