<?php


namespace App\Controller\tools;

use App\Service\ServerConnection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * clears all session data from this class
     */
    public static function clearSession()
    {
        $session = new Session();
        $session->set('userManagementUserlist', []);
    }

    /**
     * @Route("/userManagement", name="userManagementMain")
     * @IsGranted("ROLE_USER")
     */
    public function userManagement() : Response
    {
        return $this->render('tools/samba/samba.html.twig', ['userlist' => $this->getUserlist()]);
    }

    public function getUserlist()
    {
        $session = new Session();

        $userlist = $session->get('userManagementUserlist', []);
        if (empty($userlist) === false) {
            return $userlist;
        }

        $em = $this->getDoctrine()->getManager();
        $conn = new ServerConnection($em, $session);

        if ($conn->tryConnect()) {
            $conn->connect();
            $session = new Session();

            $users = $conn->exec(
                'getent shadow | grep --invert-match ":\*" | grep --invert-match ":\!" | cut -d: -f1'
            )->getOutput()
            ;

            if (empty($users) === false) {
                $userlist = explode(PHP_EOL, $users);
            }

            $session->set('userManagementUserlist', $userlist);
        }

        return $userlist;
    }
}
