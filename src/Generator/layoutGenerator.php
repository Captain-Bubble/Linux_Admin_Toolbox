<?php


namespace App\Generator;


use Symfony\Component\Finder\Finder;

class layoutGenerator {

	public function menu() {

		$finder = new Finder();

		$menu = [];
		$finder->in( __DIR__ .'/../Controller/tools/')->files()->name( '*.php');
		if ($finder->hasResults()) {
			foreach ($finder as $files) {
				$n = 'App\\Controller\\tools\\'.$files->getFilenameWithoutExtension();
				$tm = $n::menu();
				if (empty( $tm) === false) {
					$menu[] = $tm;
				}
			}
		}
		return $menu;
	}

}