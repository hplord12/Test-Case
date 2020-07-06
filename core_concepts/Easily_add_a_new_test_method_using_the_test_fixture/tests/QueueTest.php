<?php

use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase
{
    protected $queue;
    
    protected function setUp(): void
    {
        $this->queue = new Queue;        
    }

    public function testNewQueueIsEmpty()
    {
        $this->assertEquals(0, $this->queue->getCount());
    }

    public function testAnItemIsAddedToTheQueue()
    {
        $this->queue->push('green');
        
        $this->assertEquals(1, $this->queue->getCount());
    }

    public function testAnItemIsRemovedFromTheQueue()
    {
        $this->queue->push('green');
                
        $item = $this->queue->pop();
        
        $this->assertEquals(0, $this->queue->getCount());

        $this->assertEquals('green', $item);
    }
    
    public function testAnItemIsRemovedFromTheFrontOfTheQueue()
    {
        $this->queue->push('first');
        $this->queue->push('second');
        
        $this->assertEquals('first', $this->queue->pop());
    }    
}
