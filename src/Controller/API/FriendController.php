<?php

namespace App\Controller\API;

use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

/**
 * FriendController
 * @Rest\Route("/api/friend", name="api_friend")
 */
class FriendController extends AbstractFOSRestController
{
    /**
     * Add friend
     * @Rest\Get("/add/{email}")
     */
    public function addFriend($email)
    {
        /** @var User $user */
        $user = $this->getUser();
        $newFriend = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($newFriend !== null) {
            $em = $this->getDoctrine()->getManager();
            $user->addFriend($newFriend);
            $em->persist($user);
            $em->flush();
        } else {
            return new Response('User not found', 500);
        }
        return new Response('Success', 200);
    }

    /**
     * Get all friends
     * @Rest\Get("/all")
     */
    public function getMyFriends()
    {
        /** @var User $user */
        $user = $this->getUser();
        $friends = [];
        $friendsDB = $user->getFriend();
        $i = 0;
        /** @var User $value */
        foreach ($friendsDB->getValues() as $value) {
            $friends[] = [
                'firstname' => $value->getFirstname(),
                'lastname' => $value->getLastname(),
                'averagePerDay' => $value->getAveragePerDay(),
            ];
        }

        if ($friends === null) {
            return new Response('{}', 500, ['Content-Type' => 'application/json']);
        }

        return $this->handleView($this->view($friends));
    }

    /**
     * Get my friendStat
     * @Rest\Get("/all/user-stat")
     */
    public function getMyFriendsStats()
    {
        /** @var User $user */
        $user = $this->getUser();
        $friends = [];
        $friendsDB = $user->getFriend();
        $i = 0;
        $friendStats = [];
        if ($friendsDB !== null) {
            /** @var User $value */
            foreach ($friendsDB->getValues() as $value) {
                if ($value->getUserStats() !== null) {
                    $friendStat = $value->getUserStats()->getValues()[array_key_last($value->getUserStats()->getValues())];
                    $friendStats[] = [
                        'id' => $friendStat->getId(),
                        'firstname' => $value->getFirstname(),
                        'lastname' => $value->getLastname(),
                        'title' => $friendStat->getTitle(),
                        'date' => $friendStat->getDate(),
                        'moneyEco' => $friendStat->getMoneyEconomised(),
                        'cigarettes' => $friendStat->getCigarettesSaved(),
                        'lifetime' => $friendStat->getLifetimeSaved(),
                    ];
                }
            }
        }

        if ($friendStats === null) {
            return new Response('{}', 500, ['Content-Type' => 'application/json']);
        }

        return $this->handleView($this->view($friendStats));
    }
}