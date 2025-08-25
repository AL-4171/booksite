# BookSite (PHP + Bootstrap)

A clean, elegant book upload site built with PHP (PDO + MySQL) and Bootstrap 5.

## Features
- Login, register, logout, and delete account.
- Upload books with **any file extension** (up to 50 MB).
- Edit title and optionally replace the file.
- Delete books (and physical files).
- View/download individual books.
- **Home** (all books) and **MyBooks** (yours only) nav options.
- After logout you see **Login**. After account deletion you’re sent to **Sign Up**.
- Minimal CSRF protection and secure password hashing.
- Polished Bootstrap styling.

## Database
- One database named `booksite` containing **two tables** (named per your request):
  - `users_books` — stores users
  - `books_tables` — stores uploaded books
- SQL to create everything is in `init.sql`.

> If you absolutely need two separate databases/schemas instead of two tables, duplicate the `USE` section in `init.sql` with a second schema and update table references in `config.php` accordingly.

## Quick Start
1. Create DB and tables:
   ```sql
   SOURCE init.sql;
   ```
   Or manually:
   ```sql
   CREATE DATABASE IF NOT EXISTS booksite;
   USE booksite;
   -- then run the two CREATE TABLE statements inside init.sql
   ```

2. Set your DB credentials in `config.php`:
   ```php
   $DB_HOST = 'localhost';
   $DB_NAME = 'booksite';
   $DB_USER = 'root';
   $DB_PASS = '';
   ```

3. Put this project in your PHP server docroot (e.g., `htdocs/` or `public_html/`).
4. Ensure the server can write to `/uploads` (create it or let the app create it).

## Folder Structure
```
/
  index.php
  config.php
  init.sql
  /auth
    login.php register.php logout.php delete_account.php
  /books
    create.php mybooks.php view.php edit.php delete.php
  /partials
    header.php navbar.php footer.php
  /uploads  (generated at runtime)
  /assets/css/styles.css
```

## Notes
- Max file size is controlled by `$MAX_FILE_SIZE` (50 MB). For bigger uploads you may also need to raise `upload_max_filesize` and `post_max_size` in `php.ini`.
- File downloads use the stored filename; we also show the original filename for clarity.
- Foreign keys cascade-delete books when you delete an account; the script also removes physical files.
- Tested with PHP 8+.

— Generated on 2025-08-24
