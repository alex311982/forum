<?php

include_once('app/frame/core/Autoloader.php');
AutoLoader::registerDirectory(array(
        'app/frame/core/'
        , 'app/model/production3/'
    )
);

class OnSubmitCommentFormTest extends PHPUnit_Framework_TestCase
{
    protected $object;
    protected $dispatcher;

    public function setUp()
    {
        $this->object = new Object();
        $this->dispatcher = EventDispatcher::getInstance();
        $this->dispatcher->setEventName('onSubmit');
        $this->dispatcher->setData($this->object);
        $this->dispatcher->observers = array(
            'onSubmit' => array(
                new OnSubmitCommentForm()
            )
        );
    }

    public function testEmpty()
    {
        $this->object->comment = '';
        $this->dispatcher->notify();
        $this->assertEmpty('', $this->object->comment);
    }

    public function testReplaceEmotionHappy()
    {
        $this->object->comment = 'Test comment with happy emotion :)';
        $this->dispatcher->notify();
        $this->assertEquals('Test comment with happy emotion <img width="15" height="15" src="template/images/happy.png" />', $this->object->comment);
    }

    public function testReplaceEmotionSad()
    {
        $this->object->comment = 'Test comment with sad emotion :(';
        $this->dispatcher->notify();
        $this->assertEquals('Test comment with sad emotion <img width="15" height="15" src="template/images/sad.png" />', $this->object->comment);
    }

    public function testWithoutEmotion()
    {
        $this->object->comment = 'Test comment without emotion';
        $this->dispatcher->notify();
        $this->assertEquals('Test comment without emotion', $this->object->comment);
    }

    public function testNotReplaceEmotion()
    {
        $this->object->comment = 'Test comment without emotion :--)';
        $this->dispatcher->notify();
        $this->assertEquals('Test comment without emotion :--)', $this->object->comment);
    }
}