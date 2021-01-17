<?php


namespace App\Service;


use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private $submissionService;
    public function __construct(SubmissionService $service)
    {
        $this->submissionService = $service;
    }

    public function createNewUser($data) {
        $user = new Users();
        $user->setEmail($data['email']);
        $user->setResult(null);
        return $user;
    }

    public function calculateMbtiAndSaveUserResult($user, $dimensions, $singleDimension, EntityManagerInterface $em) {
        $result = $this->submissionService->getSubmissionResult($dimensions, $singleDimension);
        $user->setResult($result);
        $em->flush();
    }

}