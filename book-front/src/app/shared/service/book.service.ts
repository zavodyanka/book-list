import { environment } from './../../../environments/environment';
import { IBook } from './../interface/book';
import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse, HttpHeaders } from '@angular/common/http';
import { Observable, throwError, of } from 'rxjs';
import { catchError, tap, map } from 'rxjs/operators';

@Injectable({
    providedIn: 'root',
})
export class BookService {
    private booksUrl = environment.apiUrl + 'books';

    constructor(private http: HttpClient){

    }

    getBooks(): Observable<IBook[]> {
        return this.http.get<IBook[]>(this.booksUrl)
        .pipe(
            tap(data => console.log(JSON.stringify(data))),
            catchError(this.handleError)
        );
    }

    getBook(id: number): Observable<IBook> {
        if (id === 0) {
            return of(this.initBook());
        }

        return this.http.get<IBook>(`${this.booksUrl}/${id}`)
        .pipe(
            tap(data => console.log(JSON.stringify(data))),
            catchError(this.handleError)
        );
    }

    private initBook(): IBook {
        return {
            id: 0,
            name: '',
            author: '',
            categories: null
        };
    }

    createBook(book: IBook): Observable<IBook> {
        const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
        book.id = null;
        return this.http.post<IBook>(this.booksUrl, book, { headers })
          .pipe(
            tap(data => console.log('createbook: ' + JSON.stringify(data))),
            catchError(this.handleError)
          );
      }
    
      deleteBook(id: number): Observable<{}> {
        const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
        const url = `${this.booksUrl}/${id}`;
        return this.http.delete(url, { headers })
          .pipe(
            catchError(this.handleError)
          );
      }
    
      updateBook(book: IBook): Observable<IBook> {
        const headers = new HttpHeaders({ 'Content-Type': 'application/json' });
        const url = `${this.booksUrl}/${book.id}`;
        return this.http.put<IBook>(url, book, { headers })
          .pipe(
            tap(() => console.log('updatebook: ' + book.id)),
            map(() => book),
            catchError(this.handleError)
          );
      }
    

    private handleError(err: HttpErrorResponse) {
        let errorMessage = '';
        if (err.error instanceof ErrorEvent) {
          errorMessage = `An error occurred: ${err.error.message}`;
        } else {
          errorMessage = `Server returned code: ${err.status}, error message is: ${err.message}`;
        }
        console.error(errorMessage);
     
        return throwError(errorMessage);
    }

}