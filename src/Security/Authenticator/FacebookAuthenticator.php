<?php

namespace App\Security\Authenticator;

use App\Entity\User; // your user entity
use App\Event\UserNeedPasswordEvent;
use App\Event\UserRegisteredEvent;
use App\Utils\Factory\UserFactory;
use App\Utils\Generator\PasswordGenerator;
use App\Utils\Manager\UserManager;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FacebookAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $router;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var Session
     */
    private $session;

    public function __construct(ClientRegistry $clientRegistry, RouterInterface $router, UserManager $userManager, SessionInterface $session, EventDispatcherInterface $eventDispatcher)
    {
        $this->clientRegistry = $clientRegistry;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->userManager = $userManager;
        $this->session = $session;
    }

    public function supports(Request $request)
    {
        return 'connect_facebook_check' === $request->attributes->get('_route');
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getFacebookClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->getFacebookClient()->fetchUserFromToken($credentials);

        $email = $facebookUser->getEmail();

        $existingUser = $this->userManager->getRepository()->findOneBy([
            'facebookId' => $facebookUser->getId(),
        ]);

        if ($existingUser) {
            return $existingUser;
        }

        $user = $this->userManager->getRepository()->findOneBy(['email' => $email]);

        if (!$user) {
            $user = UserFactory::createFromFacebookUser($facebookUser);

            $plainPassword = PasswordGenerator::generatePassword();
            $this->userManager->encodePassword($user, $plainPassword);

            $this->userManager->save($user);

            $this->eventDispatcher->dispatch(new UserNeedPasswordEvent($user, $plainPassword));
            $this->eventDispatcher->dispatch(new UserRegisteredEvent($user));
            $this->session->getFlashBag()->add('success', 'An email has been sent. Please check your inbox to complete registration.');
        }

        $user->setFacebookId($facebookUser->getId());
        $this->userManager->save($user);

        return $user;
    }

    /**
     * @return FacebookClient
     */
    private function getFacebookClient()
    {
        return $this->clientRegistry->getClient('facebook_main');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetUrl = $this->router->generate('profile_index');

        return new RedirectResponse($targetUrl);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    // ...
}
