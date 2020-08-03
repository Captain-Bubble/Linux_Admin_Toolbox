<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class new_setup extends AbstractController {


	/**
	 * @Route("/newSetup", methods={"GET"})
	 */
	public function setup ( TokenStorageInterface $token ) {
		$user = $token->getToken()->getUser();


		/** making sure, no one can get to this point after first setup */
		if ( $user instanceof User ) {
			return $this->redirect( '/' );
		}

		$uc = $this->getDoctrine()->getManager()->getRepository( User::class );
		if ( empty( $uc->findAll() ) === false ) {
			return $this->redirect( '/login');
		}


		/** only if no one exists in the database */
		return $this->render( 'newSetup.html.twig' );
	}

	/**
	 * @Route("/newSetup", methods={"POST"}, name="login")
	 */
	public function setup_post ( TokenStorageInterface $token ) {


	}

}

