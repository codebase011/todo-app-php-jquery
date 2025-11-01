# todo-app-php-jquery
# Todo App

A simple todo list application built with PHP and jQuery, demonstrating basic CRUD operations without using a framework.

## Features

- Create, read, update and delete todo items
- REST API endpoints
- Real-time updates using jQuery AJAX
- MySQL database storage

## Tech Stack

- **Frontend**: HTML, Bootstrap 5, jQuery
- **Backend**: PHP 8+, MySQL
- **API**: Custom REST implementation
- **Database Access**: PDO

## Project Structure

```
todo-app/
├── api.php          # REST API endpoints
├── TodoDAO.php      # Database operations
├── Todo.php         # Todo model class
├── index.php        # Main frontend page
├── js/
│   └── app.js      # Frontend logic
└── css/
    └── styles.css   # Custom styles
```

## Setup

1. Create MySQL database:
```sql
CREATE DATABASE todo_app;
CREATE TABLE todo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

2. Clone repository and configure database connection in `TodoDAO.php`

3. Start PHP development server:
```bash
php -S localhost:8000
```

4. Access application