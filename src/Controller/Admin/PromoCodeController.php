<?php

namespace App\Controller\Admin;

use App\Entity\PromoCode;
use App\Form\Admin\PromoCodeEditFormType;
use App\Repository\PromoCodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/promo-code", name="admin_promo_code_")
 */
class PromoCodeController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(PromoCodeRepository $promoCodeRepository): Response
    {
        $promoCodeList = $promoCodeRepository->findBy(['isDeleted' => false], ['id' => 'DESC']);

        return $this->render('admin/promo-code/list.html.twig', [
            'promoCodeList' => $promoCodeList,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     */
    public function edit(Request $request, PromoCode $promoCode = null): Response
    {
        if (!$promoCode) {
            $promoCode = new PromoCode();
        }

        $form = $this->createForm(PromoCodeEditFormType::class, $promoCode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$promoCode->getValue()) {
                $promoCode->setValue(uniqid());
            }
            $promoCode->setValue(strtoupper($promoCode->getValue()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($promoCode);
            $entityManager->flush();

            return $this->redirectToRoute('admin_promo_code_list');
        }

        return $this->render('admin/promo-code/edit.html.twig', [
            'promoCode' => $promoCode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(PromoCode $promoCode): Response
    {
        if ($promoCode) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($promoCode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_promo_code_list');
    }
}
