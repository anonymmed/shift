<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Entity\Submissions;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubmissionController extends AbstractController
{
    /**
     * @Route("/v1/SubmitResult", name="submission", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);
        if (isset($data['email'])) {
            /**
             * @var $user Users|null
             */
            $user = $em->getRepository(Users::class)->findOneBy(['email' => $data['email']]);

            /*
             * Get All Dimensions
             */
            $dimensionsData = $em->getRepository(Questions::class)->getDimensions();

            /*
             * Parse and Initialize Dimensions
             */
            $dimensions = [];
            $singleDimension = [];
            foreach ($dimensionsData as $dimension) {
                $dimensions[] = $dimension['dimension'];
                $singleDimension[$dimension['dimension'][0]] = 0;
                $singleDimension[$dimension['dimension'][1]] = 0;
            }
            if ($user) {
                $em->getRepository(Submissions::class)->deleteUserSubmissions($user->getId());
                foreach ($data['answers'] as $key => $answer) {
                    if ($answer != null) {
                        $submission = new Submissions();
                        $submission->setUser($user);
                        /**
                         * @var $question Questions|null
                         */
                        $question = $em->getRepository(Questions::class)->findOneBy(['id' => $key]);
                        $submission->setQuestion($question);
                        $submission->setAnswer($answer);
                        switch ($question->getDirection()) {
                            case 1:
                                if ($answer > 4) {
                                    $singleDimension[$question->getDimension()[1]]++;
                                } else if ($answer < 4) {
                                    $singleDimension[$question->getDimension()[0]]++;
                                }
                                break;
                            case -1:

                                if ($answer > 4) {
                                    $singleDimension[$question->getDimension()[0]]++;
                                } else if ($answer < 4) {
                                    $singleDimension[$question->getDimension()[1]]++;
                                }
                        }
                        $em->persist($submission);
                    }
                }
            } else {
                $user = new Users();
                $user->setEmail($data['email']);
                $user->setResult(null);
                $em->persist($user);
                foreach ($data['answers'] as $key => $answer) {
                    if ($answer != null) {
                        $submission = new Submissions();
                        $submission->setUser($user);
                        /**
                         * @var $question Questions|null
                         */
                        $question = $em->getRepository(Questions::class)->findOneBy(['id' => $key]);
                        $submission->setQuestion($question);
                        $submission->setAnswer($answer);
                        switch ($question->getDirection()) {
                            case 1:
                                if ($answer > 4) {
                                    $singleDimension[$question->getDimension()[1]]++;
                                } else if ($answer < 4) {
                                    $singleDimension[$question->getDimension()[0]]++;
                                }
                                break;
                            case -1:

                                if ($answer > 4) {
                                    $singleDimension[$question->getDimension()[0]]++;
                                } else if ($answer < 4) {
                                    $singleDimension[$question->getDimension()[1]]++;
                                }
                        }
                        $em->persist($submission);
                    }
                }
            }
        }
        $result = '';
        foreach ($dimensions as $dimension) {
            $result1 = $singleDimension[$dimension[0]];
            $result2 = $singleDimension[$dimension[1]];
            if ($result1 >= $result2) {
                $result.= $dimension[0];
            } else {
                $result.= $dimension[1];
            }
        }
        $user->setResult($result);
        $em->flush();
        return $this->json([
            'success' => true,
            'result' => $result,
        ]);
    }
}
