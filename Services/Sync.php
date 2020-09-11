<?php
namespace Kwf\SyncBaseBundle\Services;

use Kwf\SyncBaseBundle\Services\Sync\LoggerInterface;
use Kwf\SyncBaseBundle\Services\Sync\RetrieverInterface;
use Kwf\SyncBaseBundle\Services\Sync\SyncModelInterface;
use \Countable;

class Sync
{
    /** @var AbstractRetriever $retriever */
    protected $retriever;
    /** @var SyncModelInterface[] $syncModels */
    protected $syncModels;
    /** @var LoggerInterface $logger */
    protected $logger;

    public function __construct(RetrieverInterface $retriever, array $syncModels, LoggerInterface $logger = null)
    {
        $this->retriever = $retriever;
        $this->syncModels = $syncModels;
        $this->logger = $logger;
    }

    public function sync()
    {
        if ($this->logger) $this->logger->startSync();
        $this->retriever->prepare();
        $iterator = $this->retriever->getIterator();
        if ($iterator instanceof Countable || is_array($iterator)) {
            if ($this->logger) $this->logger->setItemCount(count($iterator));
        }
        $index = 0;
        foreach ($iterator as $item) {
            if ($this->logger) $this->logger->processItem($item, $index);
            foreach ($this->syncModels as $syncModel) {
                if ($this->logger) $this->logger->processItem($item, $index, $syncModel);
                $syncModel->updateOrCreate($item, $index);
            }
            $index++;
        }
        if ($this->logger) $this->logger->commitTransaction($index);
        foreach ($this->syncModels as $syncModel) {
            if ($this->logger) $this->logger->commitTransaction($index, $syncModel);
            $syncModel->commitTransaction($index);
        }
        if ($this->logger) $this->logger->finishedSync();
    }
}
