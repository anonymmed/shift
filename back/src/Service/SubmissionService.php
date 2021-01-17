<?php


namespace App\Service;


use App\Entity\Questions;
use App\Entity\Submissions;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

class SubmissionService
{
    public function parseAndInitializeDimensions($data, &$dimensions, &$singleDimension) {
        foreach ($data as $dimension) {
            $dimensions[] = $dimension['dimension'];
            $singleDimension[$dimension['dimension'][0]] = 0;
            $singleDimension[$dimension['dimension'][1]] = 0;
        }
    }

    public function createNewSubmission($user, $question, $answer) {
        $submission = new Submissions();
        $submission->setUser($user);
        /**
         * @var $question Questions|null
         */
        $submission->setQuestion($question);
        $submission->setAnswer($answer);
        return $submission;
    }

    public function calculateAnswerScore($question, $answer, &$singleLetterDimension) {
        switch ($question->getDirection()) {
            case 1:
                if ($answer > 4) {
                    $singleLetterDimension[$question->getDimension()[1]]++;
                } else if ($answer < 4) {
                    $singleLetterDimension[$question->getDimension()[0]]++;
                }
                break;
            case -1:

                if ($answer > 4) {
                    $singleLetterDimension[$question->getDimension()[0]]++;
                } else if ($answer < 4) {
                    $singleLetterDimension[$question->getDimension()[1]]++;
                }
        }
    }

    public function submitUserSubmissions($data, Users $user, &$singleDimension, EntityManagerInterface $em) {
        foreach ($data['answers'] as $key => $answer) {
            if ($answer != null) {
                $question = $em->getRepository(Questions::class)->findOneBy(['id' => $key]);
                $submission = $this->createNewSubmission($user, $question, $answer);

                $this->calculateAnswerScore($question, $answer, $singleDimension);
                $em->persist($submission);
            }
        }
        return $singleDimension;
    }

    public function getSubmissionResult($dimensions, $singleDimension) {
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
        return $result;
    }
}
