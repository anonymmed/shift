import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {AppComponent} from './components/app/app.component';
import {ResultComponent} from './components/result/result.component';
import {HomeComponent} from './components/home/home.component';


const routes: Routes = [
  {path: 'home', component: HomeComponent },
  {path: 'result', component: ResultComponent, pathMatch: 'full'},
  {path: '**', component: HomeComponent}

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
