<?php


namespace App\Controller;

use App\Entity\User;
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
     * @Route("/settings/linuxacc", name="settings.linuxacc")
     * @IsGranted("ROLE_USER")
     */
    public function linuxacc() : Response
    {
        return $this->render('settings/settings.html.twig');
    }
}
