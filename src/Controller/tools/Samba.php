<?php


namespace App\Controller\tools;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Samba extends AbstractController
{

    /**
     * @return string[]
     */
    public static function menu() : array
    {
        return ['url' => 'sambaMain', 'name' => 'samba', 'translation_domain' => 'samba'];
    }

    /**
     * clears all session data
     */
    public static function clearSession()
    {
    }

    /**
     * @Route("/samba", name="sambaMain")
     * @IsGranted("ROLE_USER")
     */
    public function samba()
    {
        return $this->render('tools/samba/samba.html.twig');
    }
}
