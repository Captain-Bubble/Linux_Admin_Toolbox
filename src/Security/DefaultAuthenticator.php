<?php


namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;

class DefaultAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    private $em;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * check if authenticator is used/should be used
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        return $request->attributes->get('_route', '') == 'login' && $request->isMethod('POST');
//      return true;
    }


    public function getCredentials(Request $request)
    {
        return ['us' => $request->get('username', null), 'pw' => $request->get('password', null)];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (null === $credentials || null === $credentials['us'] || null === $credentials['pw']) {
            return null;
        }
        return $this->em->getRepository(User::class)->findOneBy(['username' => $credentials['us']]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['pw']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return null;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {

        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        if ($request->isMethod('POST')) {
            $data = [
                'message' => 'Benutzer nicht eingeloggt',
                'error_key' => 'REQ_LOGIN',
                'data' => false
            ];

            return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        } else {
            return new RedirectResponse('/login');
        }
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function getPassword($credentials) : ?string
    {
        return $credentials['pw'];
    }

    protected function getLoginUrl()
    {
        return '/';
    }
}
