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
  answer1: number;
  answer2: number;
  answer3: number;
  answer4: number;
  answer5: number;
  answer6: number;
  answer7: number;
  constructor(private questionService: QuestionService) {
    this.questions = [];
    this.answer1 = 0;
    this.answer2 = 0;
    this.answer3 = 0;
    this.answer4 = 0;
    this.answer5 = 0;
    this.answer6 = 0;
    this.answer7 = 0;
  }
  getAnswer() {
    console.log(this.answer1);
  }
  ngOnInit() {
    this.questionService.getAllQuestions().then((res: IQuestion[]) => {
      this.questions = res;
    }).catch((err) => {
      console.log(JSON.stringify(err));
    });
  }
}
