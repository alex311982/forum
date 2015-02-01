<?php

function include_component($component_name, $action = '', $data = array())
{
	$object = Component::factory($component_name);
	$object->execute($action, $data);
}