<?php


namespace App\Controller;

use App\Entity\LinuxServer;
use App\Entity\User;
use LogicException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Main extends AbstractController
{


    /**
     * @Route("/")
     * @param TokenStorageInterface $token
     * @return RedirectResponse
     */
    public function main(TokenStorageInterface $token) : RedirectResponse
    {

        if (!file_exists($this->getParameter('kernel.project_dir') . $_ENV['SQLITE_PATH'])) {
            shell_exec(
                'php "'.
                $this->getParameter('kernel.project_dir')
                .'/bin/console"  doctrine:migrations:diff -n'
            );
            shell_exec(
                'php "'.
                $this->getParameter('kernel.project_dir').
                '/bin/console"  doctrine:migrations:migrate -n'
            );
        }

        $user = $token->getToken()->getUser();

        // user is logged in, redirect to dashboard
        if ($user instanceof User) {
            return $this->redirect('/dashboard');
        }

        // no user exists in database, redirect to initial setup page
        $uc = $this->getDoctrine()->getManager()->getRepository(User::class);
        if (empty($uc->findAll())) {
            return $this->redirect('/newSetup');
        }

        // user not logged in, redirect to login
        return $this->redirect('/login');
    }


    /**
     * @Route("/dashboard", name="dashboard")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function dashboard() : Response
    {

        // selecting first server as default server if no server is selected
        $session = new Session();
        $activeServer = $session->get('activeServer');
        if (empty($activeServer) === true) {
            $lsr = $this->getDoctrine()->getManager()->getRepository(LinuxServer::class);
            foreach ($lsr->findAll() as $v) {
                $session->set('activeServer', $v->getId());
                break;
            }
        }

        return $this->render('base.html.twig');
    }

    /**
     * @Route ("/logout", name="logout")
     */
    public function logout()
    {
        throw new logicException('logout function in main shouldn´t be able to run');
    }


    /**
     * @Route ("/session/set/{key}", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function setSession($key, Request $request) : JsonResponse
    {
        $session = new Session();
        $session->set($key, $request->get('val'));

        return new JsonResponse(['data' => true]);
    }

    /**
     * @Route ("/session/get/{key}", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function getSession($key) : JsonResponse
    {
        $session = new Session();
        $d = $session->get($key);

        return new JsonResponse(['data' => true, 'val' => $d]);
    }

    /**
     * @Route ("/session/clear", methods={"POST"})
     * @IsGranted("ROLE_USER")
     * @return JsonResponse
     */
    public function clearSessions() : JsonResponse
    {
        $finder = new Finder();

        $finder->in(__DIR__ .'/../Controller/tools/')->files()->name('*.php');
        if ($finder->hasResults()) {
            foreach ($finder as $files) {
                $n = 'App\\Controller\\tools\\'.$files->getFilenameWithoutExtension();
                $n::clearSession();
            }
        }
        return new JsonResponse(['data' => true]);
    }
}
