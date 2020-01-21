<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\UserType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * UserController
 * @Rest\Route("/api/user", name="api_")
 */
class UserController extends AbstractFOSRestController
{
    /**
     * List all Users
     * @Rest\Get("/all")
     */
    public function getAllUser()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->json($users);
    }

    /**
     * List all Users
     * @Rest\Get("/me")
     */
    public function getMyDetails()
    {
        $user = $this->getUser();
        return $this->json($user);
    }

    /**
     * @Rest\Post("/change-password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createform(ChangePasswordType::class);
        $data = json_decode($request->getcontent(), true);
        $form->submit($data);
        if ($form->issubmitted() && $form->isvalid() && $user !== null) {
            $em = $this->getDoctrine()->getManager();
            $user->setPassword($userPasswordEncoder->encodePassword(
                $user, $data['password']
            ));
            $em->persist($user);
            $em->flush();
            return $this->handleview($this->view(['status' => 'password changed'], response::HTTP_ACCEPTED));
        }
        return $this->handleview($this->view($form->geterrors()));
    }

    /**
     * @Rest\Post("/post")
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function postUser(Request $request, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $user = new user();
        $form = $this->createform(usertype::class, $user);
        $data = json_decode($request->getcontent(), true);
        $form->submit($data);
        if ($form->issubmitted() && $form->isvalid()) {
            $em = $this->getDoctrine()->getManager();
            $user->setPassword($userPasswordEncoder->encodePassword(
                $user, $data['password']
            ));
            $em->persist($user);
            $em->flush();
            return $this->handleview($this->view(['status' => 'User created'], response::HTTP_CREATED));
        }
        return $this->handleview($this->view($form->geterrors()));
    }
}
