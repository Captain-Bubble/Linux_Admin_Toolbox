<?php


namespace App\Controller;

use App\Entity\LinuxServer;
use App\Entity\User;
use App\Form\EditLinuxServerType;
use App\Form\EditUserAccountType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Settings extends AbstractController
{


    /**
     * @Route("/settings", name="settings")
     * @IsGranted("ROLE_USER")
     */
    public function settings() : Response
    {

        return $this->render('settings/settings.html.twig');
    }

    /**
     * @Route("/settings/useracc", name="settings.useracc")
     * @IsGranted("ROLE_USER")
     */
    public function userAcc() : Response
    {

        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();

        return $this->render('settings/settings.user.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/settings/useracc/get", name="settings.useracc.ajax", methods={"POST"})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return Response
     */
    public function editUserAccForm(Request $request) : Response
    {
        $id = $request->get('id', 0);

        if (empty($id) === true) {
            $user = new User();
        } else {
            $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);
        }

        $form = $this->createForm(EditUserAccountType::class, $user);

        return $this->render('settings/settings.user.ajax.html.twig', [
            'f' => $form->createView()
        ]);
    }

    /**
     * @Route("/settings/useracc/noe", name="settings.useracc.noe")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return Response
     */
    public function userAccNoe(Request $request) : Response
    {

        $form = $this->createForm(EditUserAccountType::class, new User());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
        } else {
            return new JsonResponse([
                'error_code' => 'SETTING_ERROR_DATA'
            ]);
        }

        if (empty($user->getUsername()) === true) {
            return new JsonResponse([
                'error_code' => 'SETTING_ERROR_USERNAME'
            ]);
        }

        if (empty($user->getPassword()) === true) {
            return new JsonResponse([
                'error_code' => 'SETTING_ERROR_PASSWD'
            ]);
        }

        $em = $this->getDoctrine()->getManager();
        if (empty($user->getId())) {
            $em->persist($user);
        }
        $em->flush();

        return new JsonResponse([
            'data' => 'SUCCESS'
        ]);
    }

    /**
     * @Route("/settings/linuxServer", name="settings.linuxServer")
     * @IsGranted("ROLE_USER")
     */
    public function linuxServer() : Response
    {
        $em = $this->getDoctrine()->getManager();
        $LinuxServer = $em->getRepository(LinuxServer::class)->findAll();

        return $this->render('settings/settings.server.html.twig', [
            'servers' => $LinuxServer
        ]);
    }

    /**
     * @Route("/settings/linuxServer/get", name="settings.linuxServer.ajax", methods={"POST"})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return Response
     */
    public function editLinuxServerForm(Request $request) : Response
    {
        $id = $request->get('id', 0);

        if (empty($id) === true) {
            $server = new LinuxServer();
        } else {
            $server = $this->getDoctrine()->getManager()->getRepository(LinuxServer::class)->find($id);
        }

        $form = $this->createForm(EditLinuxServerType::class, $server);

        return $this->render('settings/settings.user.ajax.html.twig', [
            'f' => $form->createView()
        ]);
    }


    /**
     * @Route("/settings/linuxServer/noe", name="settings.linuxServer.noe")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return Response
     */
    public function linuxServerNoe(Request $request) : Response
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->get('id') != null) {
            /** @var LinuxServer $linuxServer */
            $linuxServer = $em->getRepository(LinuxServer::class)->find($request->get('id'));

        } else {
            $linuxServer = new LinuxServer();
        }
        $form = $this->createForm(EditLinuxServerType::class, $linuxServer);
        $form->submit($request->get($form->getName()), false);

        if ($form->isSubmitted() && $form->isValid()) {
            $linuxServer = $form->getData();
        } else {
            return new JsonResponse([
                'error_code' => 'SETTING_ERROR_DATA'
            ]);
        }

        if (empty($linuxServer->getUsername()) === true) {
            return new JsonResponse([
                'error_code' => 'SETTING_ERROR_SERVERNAME'
            ]);
        }

//        dd($linuxServer->getPassword());

        if (empty($linuxServer->getId()) === true) {
            $em->persist($linuxServer);
        }
        $em->flush();

        return new JsonResponse([
            'data' => 'SUCCESS'
        ]);
    }
}
