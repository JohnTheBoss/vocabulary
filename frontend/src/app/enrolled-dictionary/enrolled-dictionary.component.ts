import { Component, Input, OnInit } from '@angular/core';
import { Dictionary } from '../core/dictionary';

@Component({
  selector: 'app-enrolled-dictionary',
  templateUrl: './enrolled-dictionary.component.html',
  styleUrls: ['./enrolled-dictionary.component.css']
})
export class EnrolledDictionaryComponent implements OnInit {

  @Input() dictionary! : Dictionary;

  constructor() { }

  ngOnInit(): void {
  }

}
