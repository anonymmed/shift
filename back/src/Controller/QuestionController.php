<?php

namespace App\Controller;

use App\Entity\Questions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/api/v1/AllQuestions", name="question", methods={"GET"})
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(EntityManagerInterface $em): Response
    {
        $questions = $em->getRepository(Questions::class)->getAllQuestions();
        return $this->json($questions);
    }
}
