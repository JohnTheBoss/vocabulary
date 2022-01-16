import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { EnrolledDictionaryResponse } from './enrolledDictionaryResponse';

@Injectable({
  providedIn: 'root'
})
export class DictionaryService {

  constructor(private httpClient: HttpClient) { }

  async getAvailableDictionaries(): Promise<EnrolledDictionaryResponse | undefined> {
    return (this.httpClient.get('/api/v1/enrolled') as Observable<EnrolledDictionaryResponse>).toPromise();
  }
}
