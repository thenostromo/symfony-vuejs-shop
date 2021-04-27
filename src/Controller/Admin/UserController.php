<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\AdminType\UserEditFormType;
use App\Form\DTO\UserEditModel;
use App\Form\Handler\UserFormHandler;
use App\Repository\UserRepository;
use App\Utils\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/user", name="admin_user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(UserRepository $userRepository): Response
    {
        $userList = $userRepository->findBy(['isDeleted' => false], ['id' => 'DESC']);

        return $this->render('admin/user/list.html.twig', [
            'userList' => $userList,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     */
    public function edit(Request $request, UserFormHandler $userFormHandler, UserPasswordEncoderInterface $passwordEncoder, User $user = null): Response
    {
        $userEditModel = UserEditModel::makeFromUser($user);

        $form = $this->createForm(UserEditFormType::class, $userEditModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userFormHandler->processUserEditForm($userEditModel);

            return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(User $user, UserManager $userManager): Response
    {
        $userManager->remove($user);

        return $this->redirectToRoute('admin_user_list');
    }
}
