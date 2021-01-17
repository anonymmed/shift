import {Component, ElementRef, OnInit, ViewChild} from '@angular/core';
import {QuestionService} from '../../services/question.service';
import {SubmissionService} from '../../services/submission.service';
import {DataService} from '../../services/data.service';
import {IQuestion} from '../../contracts/IQuestion';
import Swal from 'sweetalert2/dist/sweetalert2.js';
import {Router} from '@angular/router';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {
  questions: IQuestion[];
  answers: number[];
  email: string;
  isEmailValid: boolean;
  isSubmissionValid: boolean;
  @ViewChild('emailRef', {static: false}) private emailRef: ElementRef;

  constructor(
    private questionService: QuestionService,
    private submissionService: SubmissionService,
    private dataService: DataService,
    private router: Router
  ) {
    this.questions = [];
    this.answers = [];
    this.email = '';
    this.isEmailValid = true;
    this.isSubmissionValid = true;
  }
  set data(data) {
    this.dataService.data = data;
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
      this.submissionService.submitResult(this.email, this.answers).then(async (res: any) => {
        if (res.success === true) {
          Swal.fire('Good Job!', 'You submitted your answers successfully!', 'success');
          this.data = {
            email: this.email,
            result: res.result
          };
          await this.router.navigate(['/result']);
        }
      }).catch((err) => {
        Swal.fire('Error!', `There's been an error submitting your answers! Please contact support`, 'error');
      });
    }
  }
  ngOnInit() {
    this.questionService.getAllQuestions().then((res: IQuestion[]) => {
      this.questions = res;
    }).catch((err) => {
      Swal.fire('Error!', `There's been an error getting the questions! Please contact support`, 'error');
    });
  }

}
