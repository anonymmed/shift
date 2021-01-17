<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Entity\Submissions;
use App\Entity\Users;
use App\Service\CoreService;
use App\Service\SubmissionService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubmissionController extends AbstractController
{
    /**
     * @Route("/api/v1/SubmitResult", name="submission", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param CoreService $coreService
     * @param SubmissionService $submissionService
     * @param UserService $userService
     * @return Response
     */
    public function index(
        Request $request,
        EntityManagerInterface $em,
        CoreService $coreService,
        SubmissionService $submissionService,
        UserService $userService
    ): Response
    {
        $data = $coreService->parseJsonRequest($request);
        if (isset($data['email'])) {
            $dimensions = [];
            $singleDimension = [];

            $user = $em->getRepository(Users::class)->findOneBy(['email' => $data['email']]);

            //Get All Dimensions
            $dimensionsData = $em->getRepository(Questions::class)->getDimensions();

            //Parse and Initialize Dimensions
            $submissionService->parseAndInitializeDimensions($dimensionsData, $dimensions, $singleDimension);

            if ($user) {
                $em->getRepository(Submissions::class)->deleteUserSubmissions($user->getId());
            } else {
                $user = $userService->createNewUser($data);
                $em->persist($user);
            }

            $submissionService->submitUserSubmissions($data, $user, $singleDimension, $em);

            //Calculate MBTI and save user's result
            $userService->calculateMbtiAndSaveUserResult($user, $dimensions, $singleDimension);

            $em->flush();
            return $this->json([
                'success' => true,
                'result' => $user->getResult(),
            ]);
        }
        return $this->json([
            'success' => false,
            'message' => 'No email has been provided!',
        ]);
    }
}
