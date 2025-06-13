# Cheezy Todo App

Cheezy Todo App is a lightweight Laravel-based todo list application designed to help you manage tasks efficiently. It features deadline scheduling with a calendar that highlights Indonesian national holidays and Sundays, using the [flatpickr](https://flatpickr.js.org/) datepicker and Bootstrap tooltips.

## Features

- Create, view, and validate todos with deadlines and descriptions.
- Deadline datepicker with time support.
- Highlights Sundays and Indonesian national holidays on the calendar.
- Tooltips on holidays displaying the holiday name.
- Responsive styling with Bootstrap 5.
- Uses a public API to dynamically fetch Indonesian national holiday data.

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/yourusername/cheezy-todo-app.git
    cd cheezy-todo-app
    ```
2. Install dependencies:
    ```bash
    composer install
    npm install
    npm run build
    ```
3. Copy `.env.example` to `.env` and configure environment variables, especially database connection and timezone (`APP_TIMEZONE=Asia/Jakarta`).

4. Generate application key:
    ```bash
    php artisan key:generate
    ```

5. Run migrations:
    ```bash
    php artisan migrate
    ```

6. Start the application:
    ```bash
    php artisan serve
    ```

7. Open your browser and go to [http://localhost:8000](http://localhost:8000)

## Usage

- Add new todos with descriptions and deadlines.
- The datepicker highlights Sundays and Indonesian national holidays.
- Hover over highlighted dates to see the holiday name.

## API Used

- [Indonesian National Holidays API](https://api-harilibur.vercel.app/api)

## Technologies

- Laravel 12
- Bootstrap 5
- flatpickr
- Axios
- Vite

