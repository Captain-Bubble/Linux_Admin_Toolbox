<?php


namespace App\Controller;


use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class main extends AbstractController {


	/**
	 * @Route("/")
	 * @param TokenStorageInterface $token
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function main ( TokenStorageInterface $token ) {

		if (!file_exists($this->getParameter('kernel.project_dir') . $_ENV['SQLITE_PATH'])) {
			shell_exec('php "'. $this->getParameter('kernel.project_dir') .'/bin/console"  doctrine:migrations:diff -n');
			shell_exec('php "'. $this->getParameter('kernel.project_dir') .'/bin/console"  doctrine:migrations:migrate -n');
		}

		$user = $token->getToken()->getUser();

		// user is logged in, redirect to dashboard
		if ( $user instanceof User ) {
			return $this->redirect( '/dashboard');
		}

		// no user exists in database, redirect to initial setup page
		$uc = $this->getDoctrine()->getManager()->getRepository( User::class );
		if ( empty( $uc->findAll() ) ) {
			return $this->redirect( '/newSetup' );
		}

		// user not logged in, redirect to login
		return $this->redirect( '/login' );
	}


	/**
	 * @Route("/dashboard")
	 * @IsGranted("ROLE_USER")
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function dashboard ( ) {
		return $this->render( 'base.html.twig' );
	}


}

