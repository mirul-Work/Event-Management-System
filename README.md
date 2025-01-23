<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Laravel Project Setup Guide

Follow these steps to set up the Laravel project after cloning the repository.

### 1. **Clone the Repository**
Run the following command to clone the repository:
```bash
git clone https://github.com/mirul-Work/Event-Management-System.git
cd Event-Management-System
```
### 2. **Install PHP Dependencies**
Run Composer to install all PHP dependencies:
```bash
composer install
```
### 3. **Copy the .env file**
Laravel uses an .env file for environment configuration. Make sure the .env.example file is included in the repository. After cloning, create a copy of the .env file:
```bash
cp .env.example .env
```
### 4. **Configure the .env file**
Update the .env file with your local configuration, such as database credentials, app URL, mail settings, etc.
```bash
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```
### 5. **Generate the Application Key**
Generate the APP_KEY required for encryption:
```bash
php artisan key:generate
```
### 6. **Set Up the Database**
* Create a database with the name provided in the .env file.
* Run migrations to create the necessary tables:
```bash
php artisan migrate
```
### 7. **Run user seeder to create admin**
```bash
php artisan db:seed --UserSeeder
```
### 8. **Instal Node.js Depedencies**
If your project uses frontend assets, run:
```bash
npm install
npm run dev
```
Only for production run this:
```bash
npm run build
```
### 8. **Run the Application**
Start the development server
```bash
php artisan serve
```
Your application should be accessible at http://127.0.0.1:8000.

#Thank You
