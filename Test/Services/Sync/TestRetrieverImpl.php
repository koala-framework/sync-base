<?php

namespace Kwf\SyncBaseBundle\Test\Services\Sync;

use Kwf\SyncBaseBundle\Services\Sync\AbstractRetriever;
use Kwf\SyncBaseBundle\Services\Sync\RetrieverInterface;

class TestRetrieverImpl extends AbstractRetriever
{
    private $testData;

    public function setTestData($data)
    {
        $this->testData = $data;
    }

    public function readItem($key)
    {
        return json_decode(file_get_contents($this->getDataPath() . "/$key"), true);
    }

    public function readKeys()
    {
        $dir = $this->getDataPath();
        $iterator = $this->getIterator();
        $fileList = scandir($dir);
        foreach ($fileList as $fileName) {
            if (in_array($fileName, array('.', '..')))
                continue;

            $key = $fileName;
            $iterator->append($key);
        }
    }

    public function downloadData()
    {
        foreach ($this->testData as $key => $item) {
            $file = $this->getDataPath() . '/' . $key . '.json';
            file_put_contents($file, json_encode($item));
        }
    }

    public function getDataPath()
    {
        return './test/temp/';
    }
}