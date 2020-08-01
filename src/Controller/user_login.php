<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class user_login extends AbstractController {


	/**
	 * @Route("/login", methods={"GET"})
	 */
	public function login ( TokenStorageInterface $token ) {
		$user = $token->getToken()->getUser();

		if ( $user instanceof User ) {
			return $this->redirect( '/' );
		}

		return $this->render( 'login.html.twig' );
	}

	/**
	 * @Route("/login", methods={"POST"}, name="login")
	 */
	public function login_post ( TokenStorageInterface $token ) {

		$user = $token->getToken()->getUser();

		if ( !( $user instanceof User ) ) {
			return $this->render( 'login.html.twig', [ 'error' => 'Login Fehlgeschlagen' ] );
		}

		return $this->redirect( '/' );
	}

	/**
	 * @Route("/logout", name="logout")
	 */
	public function main () {
		return $this->redirect( '/login' );
	}

}

