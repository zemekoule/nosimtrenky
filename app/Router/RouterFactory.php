<?php declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;

		// Admin
		$router[] = new Route('admin/<presenter>/<action>/<id>', array(
			'module' => 'Admin',
			'presenter' => 'Homepage',
			'action' => 'default',
			'id' => NULL,
		));

		// Front
		$router[] = new Route('<presenter>[/<action>][/<id>]', array(
			'module' => 'Front',
			'presenter' => 'Homepage',
			'action' => 'default',
			'id' => NULL,
		));

		return $router;
	}
}
