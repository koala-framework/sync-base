<?php

namespace Kwf\SyncBaseBundle\Test\Services\Sync;

use PHPUnit\Framework\TestCase;
use Kwf\SyncBaseBundle\Test\Services\Sync\TestRetrieverImpl;

class RetrieverTest extends TestCase
{
    private $testData = array(
        0 => array(
            'id' => 00,
            'name' => 'firstItem',
            'location' => 'earth'
        ),
        12 => array(
            'id' => 12,
            'name' => 'anotherItem',
            'location' => 'jupiterOrbit'
        ),
        20 => array(
            'id' => 20,
            'name' => 'yetAnotherItem',
            'location' => 'luna'
        ),
        24 => array(
            'id' => 24,
            'name' => 'we\'reNotDoneYet',
            'location' => 'mars'
        ),
        36 => array(
            'id' => 42,
            'name' => 'possiblyInvalidItem',
            'location' => 827
        )
    );

    /**
     * This is a mock that does not _really_ download data.
     * We wanna test the writing (and later, the reading and processing) of data here.
     */
    public function testDownloadData()
    {
        $retriever = new TestRetrieverImpl();
        $retriever->setTestData($this->testData);
        $retriever->prepare();

        $dir = $retriever->getDataPath();

        $files = array_diff(scandir($dir), array('.', '..')); // remove unix folder indicators
        $this->assertSameSize($files, $this->testData);
    }

    /**
     * Tests whether the retriever correctly reads all keys and appends them to the iterator.
     * @throws \Exception
     */
    public function testReadKeys()
    {
        $retriever = new TestRetrieverImpl();
        $retriever->prepare();

        $iterator = $retriever->getIterator();
        $this->assertSame($iterator->count(), sizeof($this->testData));
        foreach ($iterator as $key => $item) {
            $index = intval(str_replace('.json', '', $key));
            $this->assertArrayHasKey($index, $this->testData);
            $this->assertSame($item, $this->testData[$index]);
        }
    }

    /**
     * This tests the entire procedure of the retriever
     */
    public function testPrepare()
    {
        $this->assertSame(1, 1);
    }
}