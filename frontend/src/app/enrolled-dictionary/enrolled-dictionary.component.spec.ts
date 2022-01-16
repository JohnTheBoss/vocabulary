import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EnrolledDictionaryComponent } from './enrolled-dictionary.component';

describe('EnrolledDictionaryComponent', () => {
  let component: EnrolledDictionaryComponent;
  let fixture: ComponentFixture<EnrolledDictionaryComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EnrolledDictionaryComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EnrolledDictionaryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
