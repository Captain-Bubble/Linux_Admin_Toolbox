<?php


namespace App\Controller\tools;

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
     * @Route("/samba", name="sambaMain")
     *
     */
    public function samba()
    {
        return $this->render('tools/samba/samba.html.twig');
    }
}
