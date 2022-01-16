import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EnrolledDictionaryListComponent } from './enrolled-dictionary-list.component';

describe('EnrolledDictionaryListComponent', () => {
  let component: EnrolledDictionaryListComponent;
  let fixture: ComponentFixture<EnrolledDictionaryListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EnrolledDictionaryListComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EnrolledDictionaryListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
