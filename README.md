## Requirements

- Mysql server with an empty database to use

## Configuration

- Clone or download the repository
- run `composer install`
- cp `.env.example` to `.env` and configure database settings
- run `php artisan key:generate`
- run `php artisan migrate`
- run `php artisan db:seed`
- run `php -S localhost:8000 -t public/`
- visit `localhost:8000` in your browser

