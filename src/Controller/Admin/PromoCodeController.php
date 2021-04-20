<?php

namespace App\Controller\Admin;

use App\Entity\PromoCode;
use App\Form\Admin\PromoCodeEditFormType;
use App\Form\DTO\PromoCodeEditModel;
use App\Form\Handler\PromoCodeFormHandler;
use App\Repository\PromoCodeRepository;
use App\Utils\Manager\PromoCodeManager;
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
    public function edit(Request $request, PromoCodeFormHandler $promoCodeFormHandler, PromoCode $promoCode = null): Response
    {
        $promoCodeEditModel = PromoCodeEditModel::makeFromPromoCode($promoCode);

        $form = $this->createForm(PromoCodeEditFormType::class, $promoCodeEditModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promoCode = $promoCodeFormHandler->processPromoCodeEditForm($promoCodeEditModel);

            return $this->redirectToRoute('admin_promo_code_list', ['id' => $promoCode->getId()]);
        }

        return $this->render('admin/promo-code/edit.html.twig', [
            'promoCode' => $promoCode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(PromoCode $promoCode, PromoCodeManager $promoCodeManager): Response
    {
        $promoCode->setIsDeleted(true);
        $promoCodeManager->save($promoCode);

        return $this->redirectToRoute('admin_promo_code_list');
    }
}
