import {Component, OnInit} from '@angular/core';
import {QuestionService} from '../services/question.service';
import {IQuestion} from '../contracts/IQuestion';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit{
  title = 'front';
  questions: IQuestion[];
  answers: number[];
  constructor(private questionService: QuestionService) {
    this.questions = [];
    this.answers = [];
  }
  getAnswer() {
    console.log(this.answers);
  }
  ngOnInit() {
    this.questionService.getAllQuestions().then((res: IQuestion[]) => {
      this.questions = res;
      console.log(this.questions);
    }).catch((err) => {
      console.log(JSON.stringify(err));
    });
  }
}
