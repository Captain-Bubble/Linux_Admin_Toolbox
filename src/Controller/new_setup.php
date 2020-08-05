<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\NewSetupFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class new_setup extends AbstractController {


	/**
	 * @Route("/newSetup", methods={"GET"})
	 */
	public function setup ( TokenStorageInterface $token ) {

		if (!file_exists($this->getParameter('kernel.project_dir') . $_ENV['SQLITE_PATH'])) {
			shell_exec('php "'. $this->getParameter('kernel.project_dir') .'/bin/console"  doctrine:migrations:diff -n');
			shell_exec('php "'. $this->getParameter('kernel.project_dir') .'/bin/console"  doctrine:migrations:migrate -n');
		}

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

		$form = $this->createForm( NewSetupFormType::class, new User());

		return $this->render( 'newSetup.html.twig', [
			'f' => $form->createView()
		] );
	}

	/**
	 * @Route("/newSetup", methods={"POST"})
	 */
	public function setup_post ( Request $request, TranslatorInterface $trans, UserPasswordEncoderInterface $passwordEncoder) {

		$em = $this->getDoctrine()->getManager();

		if ( empty( $em->getRepository( User::class)->findAll() ) === false ) {
			return $this->redirect( '/login');
		}

		$form = $this->createForm( NewSetupFormType::class, new User());

		$form->handleRequest($request);

		$user = new User();
		if ($form->isSubmitted() && $form->isValid()) {
			$user = $form->getData();
		}

		if (empty( $user->getUsername()) === true) {

			return $this->render( 'newSetup.html.twig', [
				'f' => $form->createView(),
				'error' => $trans->trans( 'error_username_empty', [], 'newSetup')
			] );
		}

		if (empty( $user->getPassword()) === true) {
			return $this->render( 'newSetup.html.twig', [
				'f' => $form->createView(),
				'error' => $trans->trans( 'error_password_empty', [], 'newSetup')
			] );
		}

		$pw = $user->getPassword();

		$user->setPassword( $passwordEncoder->encodePassword( $user, $pw));

		$em->persist( $user);
		$em->flush();

		return $this->redirect( '/login');
	}

}

