<?php


namespace App\Generator;

use App\Entity\LinuxServer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Session\Session;

class LayoutGenerator extends AbstractController
{

    /**
     * generator function for Menu entries
     * @return array
     */
    public function menu() : array
    {

        $finder = new Finder();

        $menu = [];
        $finder->in(__DIR__ .'/../Controller/tools/')->files()->name('*.php');
        if ($finder->hasResults()) {
            foreach ($finder as $files) {
                $n = 'App\\Controller\\tools\\'.$files->getFilenameWithoutExtension();
                $tm = $n::menu();
                if (empty($tm) === false) {
                    $menu[] = $tm;
                }
            }
        }
        return $menu;
    }

    public function getServerList() : array
    {
        return $this->getDoctrine()->getManager()->getRepository(LinuxServer::class)->findAll();
    }

    public function getActiveServer()
    {
        $session = new Session();
        return $session->get('activeServer', 0);
    }
}
