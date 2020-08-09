import { ICategory } from './../shared/interface/category';
import { BookService } from './../shared/service/book.service';
import { IBook } from '../shared/interface/book';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-book-list',
  templateUrl: './book-list.component.html',
  styleUrls: ['./book-list.component.css']
})
export class BookListComponent implements OnInit {
  pageTitle = 'Books list';
  books: IBook[] = [];
  errorMessage = '';
  
  constructor(private bookService: BookService) {
  }

  getCategoriesString(categories: ICategory[]): string {
    return categories.map(category => category.name).join(', ');
  }

  ngOnInit(): void {
    this.bookService.getBooks().subscribe({
      next: books => this.books = books,
      error: err => this.errorMessage = err
    });
  }

}
