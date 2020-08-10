import {ICategory} from '../shared/interface/category';
import {IBook} from '../shared/interface/book';
import {AfterViewInit, Component, ElementRef, OnDestroy, OnInit, ViewChildren} from '@angular/core';
import {FormBuilder, FormControlName, FormGroup, Validators} from '@angular/forms';
import {combineLatest, fromEvent, merge, Observable, Subscription} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {BookService} from '../shared/service/book.service';
import {CategoryService} from '../shared/service/category.service';
import {GenericValidator} from '../shared/generic-validator';
import {debounceTime} from 'rxjs/operators';


@Component({
  selector: 'app-book-edit',
  templateUrl: './book-edit.component.html',
  styleUrls: ['./book-edit.component.css']
})
export class BookEditComponent implements OnInit, AfterViewInit, OnDestroy {
  @ViewChildren(FormControlName, {read: ElementRef}) formInputElements: ElementRef[];

  pageTitle = 'Book Edit';
  errorMessage: string;
  bookForm: FormGroup;
  book: IBook;
  categoryList: ICategory[];
  private sub: Subscription[] = [];
  displayMessage: { [key: string]: string } = {};
  private validationMessages: { [key: string]: { [key: string]: string } };
  private genericValidator: GenericValidator;

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private bookService: BookService,
    private categoryService: CategoryService
  ) {
    this.validationMessages = {
      name: {
        required: 'Name is required.'
      },
      author: {
        required: 'Author is required.'
      },
      categories: {
        range: 'At least one category is required.'
      }
    };

    this.genericValidator = new GenericValidator(this.validationMessages);
  }

  ngOnInit(): void {
    this.bookForm = this.fb.group({
      name: ['', [Validators.required]],
      author: ['', [Validators.required]],
      categories: [[]],
    });

    this.sub.push(this.route.paramMap.subscribe(
      params => {
        const id = +params.get('id');
        this.getBook(id);
      }
    ));
  }

  ngOnDestroy(): void {
    this.sub.forEach(subscription => subscription.unsubscribe());
  }

  ngAfterViewInit(): void {
    const controlBlurs: Observable<any>[] = this.formInputElements
      .map((formControl: ElementRef) => fromEvent(formControl.nativeElement, 'blur'));

    this.sub.push(
      merge(this.bookForm.valueChanges, ...controlBlurs).pipe(
        debounceTime(800)
      ).subscribe(value => {
        this.displayMessage = this.genericValidator.processMessages(this.bookForm);
      })
    );
  }

  getBook(id: number): void {
    const book$ = this.bookService.getBook(id);
    const categoryList$ = this.categoryService.getCategories();

    this.sub.push(
      combineLatest([book$, categoryList$])
        .subscribe(([book, categoryList]) => {
          this.categoryList = categoryList;
          this.displayBook(book);
        })
    );
  }

  displayBook(book: IBook): void {
    let categories;
    if (this.bookForm) {
      this.bookForm.reset();
    }
    this.book = book;

    if (this.book.id === 0) {
      this.pageTitle = 'Add book';
      categories = this.categoryList;
    } else {
      this.pageTitle = `Edit book: ${this.book.name}`;
      categories = this.book.categories;
    }

    // Update the data on the form
    this.bookForm.patchValue({
      name: this.book.name,
      author: this.book.author,
      categories: categories.map((category) => category.id)
    });
    // console.log('this.bookForm.value: ', this.bookForm.value);
  }

  deleteBook(): void {
    if (this.book.id === 0) {
      // Don't delete, it was never saved.
      this.onSaveComplete();
    } else {
      if (confirm(`Really delete the book: ${this.book.name}?`)) {
        this.sub.push(
          this.bookService.deleteBook(this.book.id)
            .subscribe((response) => {
              // console.log('data: ', response);
              if (response.status == 204) {
                this.onSaveComplete();
              }
            })
        );
      }
    }
  }

  saveBook(): void {
    if (this.bookForm.valid) {
      if (this.bookForm.dirty) {
        // console.log('this.book: ', this.book);
        // console.log('this.bookForm.value: ', this.bookForm.value);
        const book = {
          id: this.book.id,
          name: this.bookForm.value.name,
          author: this.bookForm.value.author,
          categories: this.bookForm.value.categories
        };
        book.categories = this.categoryList
          .filter(category => book.categories.includes(category.id))
          .map(category => {
            return category.name;
          });

        // console.log('book: ', book);

        if (this.book.id === 0) {
          this.bookService.createBook(book)
            .subscribe({
              next: () => this.onSaveComplete(),
              error: err => this.errorMessage = err
            });
        } else {
          this.bookService.updateBook(this.book.id, book)
            .subscribe({
              next: () => this.onSaveComplete(),
              error: err => this.errorMessage = err
            });
        }
      } else {
        this.onSaveComplete();
      }
    } else {
      this.errorMessage = 'Please correct the validation errors.';
    }
  }


  onSaveComplete(): void {
    this.bookForm.reset();
    this.router.navigate(['/books']);
  }
}
