import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../core/auth.service';
import { AuthLogin } from '../core/auth/login';

@Component({
  selector: 'app-auth-login',
  templateUrl: './auth-login.component.html',
  styleUrls: ['./auth-login.component.css']
})
export class AuthLoginComponent implements OnInit {

  loginForm: FormGroup = this.fb.group({
    email: ['matilda.legyek.rekak@gmail.com', Validators.required],
    password: ['R3k@123.', Validators.required],
  });

  get email(): FormControl {
    return this.loginForm.get('email') as FormControl;
  }

  get password(): FormControl {
    return this.loginForm.get('password') as FormControl;
  }

  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private router: Router,
    ) {}

  ngOnInit(): void {
  }

  async submit() {
    if(!this.loginForm.valid){
      return;
    }
    
    await this.authService.login(this.loginForm.value);
    this.router.navigate(['/']);
  }

}
