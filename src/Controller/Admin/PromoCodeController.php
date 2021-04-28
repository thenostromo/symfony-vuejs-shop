<?php

namespace App\Controller\Admin;

use App\Entity\PromoCode;
use App\Form\AdminType\PromoCodeEditFormType;
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
     * @param PromoCodeRepository $promoCodeRepository
     * @return Response
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
     *
     * @param Request              $request
     * @param PromoCodeFormHandler $promoCodeFormHandler
     * @param PromoCode|null       $promoCode
     *
     * @return Response
     */
    public function edit(Request $request, PromoCodeFormHandler $promoCodeFormHandler, PromoCode $promoCode = null): Response
    {
        $promoCodeEditModel = PromoCodeEditModel::makeFromPromoCode($promoCode);

        $form = $this->createForm(PromoCodeEditFormType::class, $promoCodeEditModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promoCode = $promoCodeFormHandler->processEditForm($promoCodeEditModel);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_promo_code_list', ['id' => $promoCode->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please, check your form!');
        }

        return $this->render('admin/promo-code/edit.html.twig', [
            'promoCode' => $promoCode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     *
     * @param PromoCode        $promoCode
     * @param PromoCodeManager $promoCodeManager
     *
     * @return Response
     */
    public function delete(PromoCode $promoCode, PromoCodeManager $promoCodeManager): Response
    {
        $promoCodeManager->remove($promoCode);

        return $this->redirectToRoute('admin_promo_code_list');
    }
}
