# Book archive

By Ania Zavodian

Application shows a list of book.

# Description

There are two projects in this repo.
PHP Rest Api (Symfony 5) is in the folder `book-api` and Angular app is in the folder `book-front`

Mysql, PHP, npm is need to start project.

# Usage

Clone this project and follow steps below:

1 Prepare application.

```
cd book-api
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

cd ../book-front
npm install
```
By default Angular has `apiUrl: 'http://localhost:8080/api/'`, if you prefer other port for API, change it in the `book-front/src/environments/environment.dev.ts`
 
2 Start server with php. 

3 Start Angular app.
```
ng serve -c dev
```

Check http://localhost:4200/books to test application.
