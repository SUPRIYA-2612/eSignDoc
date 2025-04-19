# E-Signature web based application

A Laravel-based e-signature document uploader and signer.

## Features
- Upload PDF/DOCX
- Choose Typed or Drawn Signature
- Signs and generates final signed PDF
- List of uploaded document can be view on dashboard

## Setup
1. `composer install`
2. `.env` file setup
3. `php artisan migrate`
4. `php artisan serve`

## Env File
```env
APP_NAME=Laravel E-Signer
APP_ENV=local
APP_URL=http://localhost
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=esigner_db
DB_USERNAME=root
DB_PASSWORD=

Preview Page: `/dashboard`
