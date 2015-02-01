<?php

final class Index extends AppController
{
    protected function executeIndex()
    {
        if($this->request->isPost()) {
            $nick = $this->request->post('nick');
            $comment = $this->request->post('comment');
            $this->errors = array();
            if (empty($nick)) {
                $this->errors[] = 'Nick is required';
            }
            if (empty($comment)) {
                $this->errors[] = 'Comment is required';
            }
            if (empty($this->errors)) {
                $obj = new Object();
                $obj->comment = $comment;
                EventDispatcher::getInstance()->dispatch('onSubmit', $obj);
                Model::factory('Comment')->save(
                    array(
                        'nick' => $nick,
                        'comment' => $obj->comment
                    )
                );
            } else {
                $this->nick = $nick ? $nick : '';
                $this->comment = $comment ? $comment : '';
            }
        }

        $this->comments = Model::factory('Comment')->getAll();
    }
}