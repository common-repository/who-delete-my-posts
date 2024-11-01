<?php
namespace Whodeletemyposts;

class Installation
{
	public static function install()
	{
		$db = new DB();
		$db->create_tables();
	}

	public static function uninstall()
	{
		$db = new DB();
		// comments this on production
		$db->drop_tables();
	}
}