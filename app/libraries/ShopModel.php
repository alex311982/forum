<?php
class ShopModel extends Model
{
	protected $db;
	
	public function __construct()
	{
		parent::__construct();
        $this->db = Mysql::getInstance();
	}
}
