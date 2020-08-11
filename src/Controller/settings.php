<?php


namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class settings extends AbstractController {


	/**
	 * @Route("/settings", name="settings")
	 * @IsGranted("ROLE_USER")
	 */
	public function settings ( ) {

		return $this->render( 'settings.html.twig');
	}

	/**
	 * @Route("/settings/useracc", name="settings.useracc")
	 * @IsGranted("ROLE_USER")
	 */
	public function useracc ( ) {

		return $this->render( 'settings.html.twig');
	}

	/**
	 * @Route("/settings/linuxacc", name="settings.linuxacc")
	 * @IsGranted("ROLE_USER")
	 */
	public function linuxacc ( ) {

		return $this->render( 'settings.html.twig');
	}

}

