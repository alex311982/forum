<?php

/**
 * Observer on submit form event
 *
 * @author Gubarev Alex
 */
final class OnSubmitCommentForm extends Observer
{
    protected $emoticons = array(
        ':(' => 'sad.png',
        ':)'  => 'happy.png',
        ':D'  => 'smile3.gif',
        ':-|'  => 'smile4.gif'
    );

    /**
     * Update observer by event
     *
     * @param string $event event name
     * @return void
     */
    public function update($event)
    {
        $eventName = $event->getEventName();
        $eventData = $event->getData();
        if ($eventName == 'onSubmit') {
            foreach ($this->emoticons as $emoticon => $image) {
                $eventData->comment = str_replace($emoticon,'<img width="15" height="15" src="template/images/' . $image . '" />', $eventData->comment);
            }
        }
    }
}