<?php
namespace Kwf\SyncBaseBundle\Services\Sync;

use Symfony\Component\Validator\Constraints\Count;

class BasicIterator implements \Iterator, \Countable
{
    /**
     * @var AbstractRetriever $retriever;
     */
    protected $retriever;

    /**
     * @var mixed $currentIndex;
     */
    protected $currentIndex;

    /**
     * @var array $keys
     */
    protected $keys = array();

    public function __construct(AbstractRetriever $retriever, LoggerInterface $logger = null)
    {
        $this->retriever = $retriever;
    }

    /**
     * Return the current element
     * @return mixed Can return any type.
     */
    public function current()
    {
        if (!$this->valid())
            return null;

        return $this->retriever->readItem($this->key());
    }

    /**
     * Move forward to next element
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->currentIndex++;
    }

    /**
     * Return the key of the current element
     * @return string|float|int|bool|null scalar on success, or null on failure.
     */
    public function key()
    {
        if (!$this->valid())
            return null;

        return $this->keys[$this->currentIndex];
    }

    /**
     * Checks if current position is valid
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return (key_exists($this->currentIndex, $this->keys));
    }

    /**
     * Rewind the Iterator to the first element
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->currentIndex = 0;
    }

    /**
     * Appends the provided index at the end of this iterator.
     * @param mixed $key
     */
    public function append($key)
    {
        $this->keys[] = $key;
    }

    /**
     * Returns the amount of keys present in this iterator.
     * @return int
     */
    public function count()
    {
        return count($this->keys);
    }

    /**
     * Sets all keys in one step. Use this instead of append(), not with it.
     * @param array $keys
     */
    public function setKeys(array $keys)
    {
        $this->keys = $keys;
    }
}