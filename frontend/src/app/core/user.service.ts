import { Injectable } from '@angular/core';
import { UserModel } from './userModel';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  private _user?: UserModel;

  get user(){
    return this._user;
  }

  constructor() { }

  setUser(user: UserModel) {
    this._user = user;
  }
}
