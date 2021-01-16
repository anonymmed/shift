import { Injectable } from '@angular/core';
import {HttpClient} from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class QuestionService {

  constructor(private http: HttpClient) { }
  getAllQuestions(): Promise<any> {
   return this.http.get('https://127.0.0.1:8000/AllQuestions').toPromise();
  }
}
