import { Component, OnInit } from '@angular/core';
import { Dictionary } from '../core/dictionary';
import { DictionaryService } from '../core/dictionary.service';

@Component({
  selector: 'app-enrolled-dictionary-list',
  templateUrl: './enrolled-dictionary-list.component.html',
  styleUrls: ['./enrolled-dictionary-list.component.css']
})
export class EnrolledDictionaryListComponent implements OnInit {

  availableDictionaries?: Dictionary[];

  constructor(private dictionaryService: DictionaryService ) { }

  async ngOnInit(): Promise<void> {
    const result = await this.dictionaryService.getAvailableDictionaries();
    if(result) {
      this.availableDictionaries = result.enrolled;
    }
  }

}
