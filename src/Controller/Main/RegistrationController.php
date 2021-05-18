<?php

namespace App\Controller\Main;

use App\Entity\User;
use App\Exception\EmptyUserPlainPasswordException;
use App\Form\RegistrationFormType;
use App\Messenger\Message\Event\UserRegisteredEvent;
use App\Security\Verifier\EmailVerifier;
use App\Utils\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EmailVerifier $emailVerifier, TranslatorInterface $translator)
    {
        $this->emailVerifier = $emailVerifier;
        $this->translator = $translator;
    }

    /**
     * @Route({
     *     "en": "/registration",
     *     "fr": "/créer-un-compte",
     *     "ru": "/registration",
     * }, name="app_registration")
     */
    public function registration(Request $request, UserManager $userManager, MessageBusInterface $messageBus): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('profile_index');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user->setUsername($user->getEmail());
                $userManager->encodePassword($user, $form->get('plainPassword')->getData());
                $userManager->save($user);

                $messageBus->dispatch(new UserRegisteredEvent($user->getId()));
                $this->addFlash('success', $this->translator->trans('flash.user_signup.check_your_inbox'));

                return $this->redirectToRoute('shop_index');
            } catch (EmptyUserPlainPasswordException $ex) {
                $this->addFlash('warning', $this->translator->trans('flash.user_signup.check_your_form'));
            }
        }

        return $this->render('main/security/registration.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, UserManager $userManager): Response
    {
        $id = $request->get('id');
        if (!$id) {
            return $this->redirectToRoute('shop_index');
        }

        $user = $userManager->find($id);
        if (!$user) {
            return $this->redirectToRoute('shop_index');
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request->getUri(), $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('warning', $exception->getReason());

            return $this->redirectToRoute('security_login');
        }

        $this->addFlash('success', $this->translator->trans('flash.user_signup.your_email_verified'));

        return $this->redirectToRoute('profile_index');
    }
}
