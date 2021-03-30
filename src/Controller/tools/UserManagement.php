<?php


namespace App\Controller\tools;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserManagement extends AbstractController
{

    /**
     * @return string[]
     */
    public static function menu() : array
    {
        return ['url' => 'userManagementMain', 'name' => 'usermanagement', 'translation_domain' => 'usermanagement'];
    }

    /**
     * @Route("/userManagement", name="userManagementMain")
     *
     */
    public function userManagement() : Response
    {
        return $this->render('tools/samba/samba.html.twig');
    }
}
