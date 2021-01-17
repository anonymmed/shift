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
                        $em->persist($submission);
                    }
                }
            }
            $em->flush();
        }
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SubmissionController.php',
        ]);
    }
}
