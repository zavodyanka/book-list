import { Injectable } from '@angular/core';
import { HttpInterceptor, HttpRequest,HttpHandler, HttpEvent, HttpResponse } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

@Injectable()
export class ResponseCutInterceptor implements HttpInterceptor {
  intercept(
    request: HttpRequest<any>, next: HttpHandler
  ) : Observable<HttpEvent<any>> {
    console.log(request.url);

    return next.handle(request).pipe(
      map((event: HttpEvent<any>) => {
        if (event instanceof HttpResponse) {
          const modEvent = event.clone({ body: event.body['hydra:member'] });

          return modEvent;
        }
      })
    );
  }
}
