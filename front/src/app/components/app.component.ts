import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import {QuestionService} from '../services/question.service';
import {SubmissionService} from '../services/submission.service';
import {IQuestion} from '../contracts/IQuestion';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
  title = 'front';
  questions: IQuestion[];
  answers: number[];
  email: string;
  isEmailValid: boolean;
  isSubmissionValid: boolean;
  @ViewChild('emailRef', {static: false}) private emailRef: ElementRef;

  constructor(private questionService: QuestionService, private submissionService: SubmissionService) {
    this.questions = [];
    this.answers = [];
    this.email = '';
    this.isEmailValid = true;
    this.isSubmissionValid = true;
  }
  validateSubmission() {
    this.isSubmissionValid = this.answers.filter((d: number) => d).length === this.questions.length;
    const regex = /\S+@\S+\.\S+/;
    this.isEmailValid = regex.test(this.email);
    if (!this.isEmailValid) {
      this.emailRef.nativeElement.focus();
    }
  }
  submitAnswers() {
    this.validateSubmission();
    if (this.isEmailValid && this.isSubmissionValid) {
      this.submissionService.submitResult(this.email, this.answers).then((res: any) => {
        if (res.succes) {
          console.log('finished');
        }
      }).catch((err) => {
        console.log(JSON.stringify(err));
      });
    }
  }
  ngOnInit() {
    this.questionService.getAllQuestions().then((res: IQuestion[]) => {
      this.questions = res;
    }).catch((err) => {
      console.log(JSON.stringify(err));
    });
  }
}
