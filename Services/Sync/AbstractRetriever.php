<?php
namespace Kwf\SyncBaseBundle\Services\Sync;

use Exception;
use \IteratorAggregate;
use Traversable;

/**
 * Class AbstractRetriever
 * Uses an internal prepare() function to check if getDataPath() exists,
 * storing data by calling downloadData() if the folder did not exist,
 * and finally calls readData() to feed the iterator.
 * @package Kwf\SyncBaseBundle\Services\Sync
 */
abstract class AbstractRetriever implements RetrieverInterface
{
    /**
     * @var string $dir
     */
    protected $dir;

    /**
     * @var BasicIterator $iterator
     */
    protected $iterator;

    /**
     * @var LoggerInterface logger;
     */
    public $logger;

    public function __construct(BaseIterator $iterator = null, LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        $this->setIterator($iterator);
    }

    /**
     * Prepares the retriever by setting up the data folder if it was not yet created and calling downloadData();
     * After that, readData(); is called to feed the retriever with given data.
     * This is supposed to throw exceptions if something did not work as intended.
     * Feel free to overwrite this method if you know what you're doing.
     */
    public function prepare()
    {
        $this->dir = $this->getDataPath();

        if (!is_dir($this->dir)) {
            if (!mkdir($this->dir, 0777, true) && !is_dir($this->dir)) {
                throw new Exception(sprintf('Directory "%s" could not be created', $this->dir));
            }
        }
        
        if (count(scandir($this->dir)) < 3)
            $this->downloadData();

        $this->readKeys();
    }

    /**
     * @param BasicIterator|null $iterator
     */
    public function setIterator($iterator)
    {
        if ($iterator == null)
            $iterator = new BasicIterator($this, $this->logger);
        $this->iterator = $iterator;
    }

    public function getIterator()
    {
        return $this->iterator;
    }
}
