<?php


namespace App\Controller\tools;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class samba extends AbstractController {

	public static function menu() {
		return ['url' => '/samba', 'name' => 'Samba Netzwerkfreigabe'];
	}

	/**
	 * @Route("/samba")
	 *
	 */
	public function test() {
		return new JsonResponse([]);
	}

}

