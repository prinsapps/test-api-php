## Project Setup

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-cp .env.example .env
-php artisan key:generate
-composer install
-php artisan serve

## Here are the list of apis

1. GET http://127.0.0.1:8000/api/services/CountryCode from csv

2. POST http://127.0.0.1:8000/api/services/
Request Params:
Ref
Centre
Service
Country

3. PUT http://127.0.0.1:8000/api/services/Ref from csv
Request Params:
Centre
Service
Country

NOTE: CSV file will be inside public/csv directory 
used Postman tool for rest api


