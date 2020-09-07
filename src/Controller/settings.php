<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserAccountType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class settings extends AbstractController {


	/**
	 * @Route("/settings", name="settings")
	 * @IsGranted("ROLE_USER")
	 */
	public function settings () {

		return $this->render( 'settings/settings.html.twig' );
	}

	/**
	 * @Route("/settings/useracc", name="settings.useracc")
	 * @IsGranted("ROLE_USER")
	 */
	public function useracc () {

		$users = $this->getDoctrine()->getManager()->getRepository( User::class )->findAll();

		return $this->render( 'settings/settings.user.html.twig', [
			'users' => $users
		] );
	}

	/**
	 * @Route("/settings/useracc/get", name="settings.useracc.ajax", methods={"POST"})
	 * @IsGranted("ROLE_USER")
	 */
	public function editUseraccForm ( Request $request ) {
		$id = $request->get( 'id', 0 );

		if (empty( $id) === true) {
			$user = new User();
		} else {
			$user = $this->getDoctrine()->getManager()->getRepository( User::class )->find( $id );
		}

		$form = $this->createForm( EditUserAccountType::class, $user );

		return $this->render( 'settings/settings.user.ajax.html.twig', [
			'f' => $form->createView()
		] );
	}

	/**
	 * @Route("/settings/linuxacc", name="settings.linuxacc")
	 * @IsGranted("ROLE_USER")
	 */
	public function linuxacc () {

		return $this->render( 'settings/settings.html.twig' );
	}

}

