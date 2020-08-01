<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class main extends AbstractController {


		/**
	 * @Route("/")
	 * @param TokenStorageInterface $token
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function main(TokenStorageInterface $token) {

		$user = $token->getToken()->getUser();

		if (!($user instanceof User)) {
			return $this->redirect('/login');
		}

		return $this->render('base.html.twig');
	}

}

