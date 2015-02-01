<?php
final class CommentTable extends ShopModel
{
    public function getAll()
    {
        $data = $this->db->quickSelect('SELECT * FROM `comments`', 'Comment');

        return $data;
    }

    public function save(array $data)
    {
        $data = $this->db->insert(
            'INSERT INTO `comments` SET `nick` = ?, `comment`=?',
            'ss',
            array($data['nick'], $data['comment'])
        );

        return $data;
    }
}