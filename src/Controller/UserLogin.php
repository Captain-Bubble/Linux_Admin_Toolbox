<?php


namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserLogin extends AbstractController
{


    /**
     * @Route("/login", methods={"GET"}, name="loginGet")
     * @param TokenStorageInterface $token
     * @return RedirectResponse|Response
     */
    public function login(TokenStorageInterface $token) : RedirectResponse|Response
    {
        $user = $token->getToken()->getUser();

        if ($user instanceof User) {
            return $this->redirect('/dashboard');
        }

        return $this->render('login.html.twig');
    }

    /**
     * @Route("/login", methods={"POST"}, name="login")
     * @param TokenStorageInterface $token
     * @param TranslatorInterface   $trans
     * @return RedirectResponse|Response
     */
    public function loginPost(TokenStorageInterface $token, TranslatorInterface $trans) : RedirectResponse|Response
    {

        $user = $token->getToken()->getUser();

        if (!( $user instanceof User )) {
            return $this->render('login.html.twig', [ 'error' => $trans->trans('login_error', [], 'login') ]);
        }

        return $this->redirect('/dashboard');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function main() : RedirectResponse
    {
        return $this->redirect('/login');
    }
}
