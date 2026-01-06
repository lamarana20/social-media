# Social Media (Laravel)

Small social-style Laravel 12 app with posts, comments, likes, and email-verified accounts.

## Features
- User registration, login, logout, remember-me, and email verification
- Password reset via email with token flow
- Create, edit, delete posts with optional image uploads (stored on `public` disk)
- Post likes (“j’aime”) toggle per user; comment likes per user
- Comment on posts, edit/delete own comments
- Personal dashboard for managing your posts; browse all posts on the public feed

## Tech Stack
- PHP 8.2+, Laravel 12, Blade
- MySQL/PostgreSQL/SQLite (via Laravel’s database layer)
- Tailwind CSS 4 + Vite build

## Prerequisites
- PHP 8.2+ with `pdo` extension
- Composer
- Node.js 18+
- A database (SQLite works out of the box)
- Mailer credentials for verification/reset emails

## Setup
1) Install PHP deps: `composer install`  
2) Install JS deps: `npm install`  
3) Copy env: `cp .env.example .env` and set `DB_*`, `MAIL_*`, `APP_URL`.  
4) Generate key: `php artisan key:generate`  
5) Create database (e.g., `touch database/database.sqlite`) and run migrations: `php artisan migrate`  
6) Link storage if serving images: `php artisan storage:link`  

## Run
- App: `php artisan serve`
- Assets: `npm run dev` (or `npm run build` for production)
- Combined local loop (PHP server, queue, logs, Vite) is available via Composer script `composer dev` (uses `concurrently` and may require Unix shell).

## Usage Notes
- Public feed: `/` shows latest posts; `/posts/{id}` shows a post with comments.  
- Auth required for creating posts, commenting, liking.  
- Dashboard for your own posts: `/dashboard`.  
- Email verification flow at `/email/verify`; password reset begins at `/forgot-password`.  
- Posts and comments enforce per-user ownership for edits/deletes; likes are unique per user per entity.

## Tests
- Pest is configured; run `php artisan test` (ensure a test database is configured in `.env.testing`).

## Project Structure Highlights
- `routes/web.php` — HTTP routes (auth, posts, comments, likes).  
- `app/Models` — Post, Comment, Like, JaimePost (post likes), User.  
- `app/Http/Controllers` — Auth, Post, Comment, Dashboard, ResetPassword controllers.  
- `resources/views` — Blade templates for layout, posts, auth pages.  
