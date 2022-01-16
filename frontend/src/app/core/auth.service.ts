import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

import { AuthLogin } from './auth/login';
import { LoginResponse } from './loginResponse';
import { UserService } from './user.service';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private _token?: string;

  get token() {
    return this._token;
  }

  get isLoggedIn() {
    return this._token ? true: false;
  }

  constructor(
    private httpClient: HttpClient,
    private userService: UserService,
    ) { }

  async login(authLoginRequest: AuthLogin) {
     const result = await (this.httpClient.post('/api/v1/auth/login', authLoginRequest) as Observable<LoginResponse>).toPromise();
     if(result?.success === true){
        this._token = result.token.token;
        this.userService.setUser(result.user);
     }
    }
}
