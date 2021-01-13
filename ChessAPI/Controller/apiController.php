<?php
namespace Controller;



class apiController extends AController
{
	function __call($name, $args)
	{
		$this->before($args[0], $args[1]);
		return $this->{$name}();
	}

}


 ?>
