import { IBook } from './../shared/interface/book';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { BookService } from './../shared/service/book.service';
import { ICategory } from './../shared/interface/category';

@Component({
  selector: 'app-book-detail',
  templateUrl: './book-detail.component.html',
  styleUrls: ['./book-detail.component.css']
})
export class BookDetailComponent implements OnInit {
  pageTitle = 'Book detail';
  book: IBook = null;
  errorMessage = '';

  constructor(
    private route: ActivatedRoute,
    private bookService: BookService,
    private router: Router
    ) {}

  onBack(): void {
    this.router.navigate(['/books']);
  }

  getCategoriesString(categories: ICategory[]): string {
    return categories.map(category => category.name).join(', ');
  }

  ngOnInit(): void {
    let id = +this.route.snapshot.paramMap.get('id');
    this.bookService.getBook(id).subscribe({
      next: book => this.book = book,
      error: err => this.errorMessage = err
    });
  }

}
