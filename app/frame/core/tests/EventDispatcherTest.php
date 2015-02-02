<?php

define('CORE_PTH_CONFIGS', 'configs/');

include_once('app/frame/core/Autoloader.php');
AutoLoader::registerDirectory(array(
        'app/frame/core/'
        , 'app/model/production3/'
        , 'app/libraries/'
    )
);

class EventDispatcherTest extends PHPUnit_Framework_TestCase
{
    public $db;
    public $testEventName = 'test_event';
    public $testObserverName = 'TestObserver';
    public $testComment = 'Test comment';
    public $validEventName = 'onSubmit';
    public $testData = 'testData';
    public $queryInsert = 'INSERT INTO `observers` SET `event_type`="onSubmit", `observer_name`="OnSubmitCommentForm"';
    public $queryTruncate = 'TRUNCATE TABLE `observers`';

    public function setUp()
    {
        $this->db = Mysql::getInstance();
        $this->db->query($this->queryTruncate);
        $this->dispatcher = EventDispatcher::getInstance();
    }

    public function testAttachValidObserver()
    {
        $this->dispatcher->attach($this->testEventName, $this->testObserverName);
        $result = $this->db->getOne(
            'SELECT `observer_name` FROM `observers` WHERE `event_type`="' . $this->testEventName
            . '" AND `observer_name`="' . $this->testObserverName . '"'
        );
        $this->assertEquals(1, count($result));
    }

    public function testDetachValidObserver()
    {
        $this->dispatcher->detach($this->testEventName, $this->testObserverName);
        $result = $this->db->getOne(
            'SELECT `observer_name` FROM `observers` WHERE `event_type`="' . $this->testEventName
            . '" AND `observer_name`="' . $this->testObserverName . '"'
        );
        $this->assertEquals(0, count($result));
    }

    public function testAttachNotValidObserverType()
    {
        $this->db->query($this->queryTruncate);
        $this->dispatcher->attach('', $this->testObserverName);
        $result = $this->db->getOne(
            'SELECT `observer_name` FROM `observers` WHERE `event_type`="' . $this->testEventName
            . '" AND `observer_name`="' . $this->testObserverName . '"'
        );
        $this->assertEquals(0, count($result));
    }

    public function testAttachNotValidObserverName()
    {
        $this->db->query($this->queryTruncate);
        $this->dispatcher->attach($this->testEventName, '');
        $result = $this->db->getOne(
            'SELECT `observer_name` FROM `observers` WHERE `event_type`="' . $this->testEventName
            . '" AND `observer_name`="' . $this->testObserverName . '"'
        );
        $this->assertEquals(0, count($result));
    }

    public function testAttachNotValidObserverBoth()
    {
        $this->db->query($this->queryTruncate);
        $this->dispatcher->attach('', '');
        $result = $this->db->getOne(
            'SELECT `observer_name` FROM `observers` WHERE `event_type`="' . $this->testEventName
            . '" AND `observer_name`="' . $this->testObserverName . '"'
        );
        $this->assertEquals(0, count($result));
    }

    public function testNotifyValidObserver()
    {
        $this->db->query($this->queryTruncate);
        $this->db->query($this->queryInsert);
        $this->object = new Object();
        $this->object->comment = $this->testComment;
        $this->dispatcher->dispatch($this->validEventName, $this->object);
        $this->assertEquals(1, count($this->dispatcher->observers[$this->validEventName]));
    }

    public function testNotifyNotObserver()
    {
        $this->db->query($this->queryTruncate);
        $this->object = new Object();
        $this->object->comment = $this->testComment;;
        $this->dispatcher->dispatch($this->testEventName, $this->object);
        if (empty($this->dispatcher->observers[$this->testEventName])) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }
    }

    public function testSetData()
    {
        $this->dispatcher->setData($this->testData);
        $this->assertEquals($this->testData, $this->dispatcher->getData());
    }

    public function testSetEventName()
    {
        $this->dispatcher->setEventName($this->testEventName);
        $this->assertEquals($this->testEventName, $this->dispatcher->getEventName());
    }

    public function testSetEmptyEventName()
    {
        $this->dispatcher->setEventName('');
        $this->assertNotEmpty($this->dispatcher->getEventName());
    }
}