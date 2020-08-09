import { environment } from '../../../environments/environment';
import { ICategory } from '../interface/category';
import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse, HttpHeaders } from '@angular/common/http';
import { Observable, throwError, of } from 'rxjs';
import { catchError, tap, map } from 'rxjs/operators';

@Injectable({
    providedIn: 'root',
})
export class CategoryService {
    private categoriesUrl = environment.apiUrl + 'categories';

    constructor(private http: HttpClient){

    }

    getCategories(): Observable<ICategory[]> {
        return this.http.get<ICategory[]>(this.categoriesUrl)
        .pipe(
            tap(data => console.log(JSON.stringify(data))),
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