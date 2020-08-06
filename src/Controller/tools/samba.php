<?php


namespace App\Controller\tools;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class samba extends AbstractController {

	public static function menu() {
		return ['url' => '/samba', 'name' => 'samba', 'translation_domain' => 'samba'];
	}

	/**
	 * @Route("/samba")
	 *
	 */
	public function samba() {
		return $this->render( 'tools/samba.html.twig');
	}

}

