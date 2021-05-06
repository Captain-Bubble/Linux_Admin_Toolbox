<?php


namespace App\Controller\tools;

use App\Service\ServerConnection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $conn = new ServerConnection($em, $session);

        if ($conn->tryConnect()) {
            $conn->connect();
            $conn->exec('ls');
            $conn->exec('getent shadow | grep --invert-match ":\*" | grep --invert-match ":\!" | cut -d: -f1');
            dump($conn->getOutput());
        }

        return $this->render('tools/samba/samba.html.twig');
    }
}
