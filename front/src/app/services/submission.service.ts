import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class SubmissionService {
  constructor(private http: HttpClient) { }

  submitResult(email: string, answers: number[]) {
    const httpOptions = {
      headers: new HttpHeaders({
        'Access-Control-Allow-Origin' : '*'
      })
    };

    const body = {
      email,
      answers
    };
    return this.http.post('/api/v1/SubmitResult', body, httpOptions).toPromise();
  }

}

