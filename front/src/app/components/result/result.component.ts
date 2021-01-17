import { Component, OnInit } from '@angular/core';
import {DataService} from '../../services/data.service';
import {Router} from '@angular/router';

@Component({
  selector: 'app-result',
  templateUrl: './result.component.html',
  styleUrls: ['./result.component.css']
})
export class ResultComponent implements OnInit {
  constructor(private dataService: DataService, private router: Router) { }
  get data() {
    return this.dataService.data;
  }
  resultInclude(singleDimension: string) {
    if (this.data) {
      return this.data.result.includes(singleDimension);
    }
    return;
  }

  async ngOnInit() {
   if (this.data === undefined) {
      await this.router.navigate(['/home']);
    }
  }
}
