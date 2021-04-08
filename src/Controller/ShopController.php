<?php

namespace App\Controller;

use App\Form\ContactUsFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/", name="shop_index")
     */
    public function index(): Response
    {
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
        ]);
    }

    /**
     * @Route("/about", name="shop_about")
     */
    public function about(): Response
    {
        return $this->render('shop/about.html.twig', [
        ]);
    }

    /**
     * @Route("/contact-us", name="shop_contact_us")
     */
    public function contactUs(Request $request): Response
    {
        $data = [
            'email' => null,
            'name' => null,
            'letterTitle' => null,
            'letterText' => null,
        ];
        $form = $this->createForm(ContactUsFormType::class, $data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];
            $name = $form->getData()['name'];
            $letterTitle = $form->getData()['letterTitle'];
            $letterText = $form->getData()['letterText'];

            $templatedEmail = (new TemplatedEmaiL())
                ->from(new Address('robot@ranked-choice.com', 'RankedChoice Shop Bot'))
                ->to('contact@ranked-choice.com')
                ->subject('[CONTACT US] '.$letterTitle)
                ->htmlTemplate('email/contact_us.html.twig');

            $context = $templatedEmail->getContext();
            $context['clientEmail'] = $email;
            $context['clientName'] = $name;
            $context['letterTitle'] = $letterTitle;
            $context['letterText'] = $letterText;

            $templatedEmail->context($context);

            $this->mailer->send($templatedEmail);

            return $this->redirectToRoute('shop_contact_us');
        }

        return $this->render('shop/contact_us.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
