<?php

namespace App\Controller\Main;

use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Exception\EmptyUserPlainPasswordException;
use App\Form\RegistrationFormType;
use App\Security\Verifier\EmailVerifier;
use App\Utils\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/registration", name="app_registration")
     */
    public function registration(Request $request, UserManager $userManager, EventDispatcherInterface $eventDispatcher): Response
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

                $eventDispatcher->dispatch(new UserRegisteredEvent($user));
                $this->addFlash('success', 'An email has been sent. Please check your inbox to complete registration.');

                return $this->redirectToRoute('shop_index');
            } catch (EmptyUserPlainPasswordException $ex) {
                $this->addFlash('warning', 'Please, check your email/password');
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
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('warning', $exception->getReason());

            return $this->redirectToRoute('security_login');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('profile_index');
    }
}
