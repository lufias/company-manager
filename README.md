# Company Manager

A modern web application built with Laravel for managing company information and profiles. This application provides a clean, intuitive interface for creating, viewing, editing, and managing company records.

<div style="background-color: #ff8c00; padding: 15px; border-radius: 8px; border-left: 5px solid #ff6600; margin: 20px 0;">
<h2 style="color: white; margin-top: 0;">🚀 Demo Ready!</h2>
<p style="color: white; font-weight: bold; margin-bottom: 8px;">
<strong>Note:</strong> While <code style="background-color: rgba(255,255,255,0.2); padding: 2px 6px; border-radius: 3px; color: white;">.env</code> files should normally NOT be included in repositories, this demo project includes one for easy setup and testing.
</p>
<p style="color: white; margin-bottom: 0;">
Just clone and run - no configuration needed. You may update the file if required
</p>
</div>

## Features

- **Company Management**: Create, read, update, and delete company records
- **User Authentication**: Secure login and registration system
- **Company Profiles**: Store comprehensive company information including:
  - Company name
  - Email address
  - Website URL
  - Company logo upload
  - Creation tracking (who created each company)
- **Responsive Design**: Modern, mobile-friendly user interface

## Tech Stack

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend**: Blade templates with modern CSS/JavaScript
- **Database**: MySQL (With SQLite options ready)
- **File Storage**: Local file system for logo uploads
- **Authentication**: Laravel's built-in authentication system

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- MySQL (or SQLite if preferred)
- Docker (optional)

## Installation

### Option 1: Docker Installation (Recommended)

The easiest way to get started is using Docker:

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd company-manager
   ```

2. **Start the application**
   
   ```bash
   docker compose up -d
   ```
   
3. **Run the setup script**
   
   ```bash
   ./setup.sh
   ```

The setup script will:
- Run database migrations
- Create storage symbolic links
- Seed the database with sample data
- Set up the application for immediate use

Your application will be available at `http://localhost:8000`

<div style="background-color: #ff8c00; padding: 15px; border-radius: 8px; border-left: 5px solid #ff6600; margin: 20px 0;">
<h3 style="color: white; margin-top: 0;">⚠️ Troubleshooting</h3>
<p style="color: white; font-weight: bold; margin-bottom: 0;">
<strong>Database Seeding Issue:</strong> If you encounter <code style="background-color: rgba(255,255,255,0.2); padding: 2px 6px; border-radius: 3px; color: white;">Illuminate\Database\UniqueConstraintViolationException</code> during seeding or when running <code style="background-color: rgba(255,255,255,0.2); padding: 2px 6px; border-radius: 3px; color: white;">setup.sh</code>, simply run the command again. This is just a database unique constraint issue that resolves on retry.
</p>
</div>

### Option 2: Manual Installation

If you prefer to install manually without Docker:

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd company-manager
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   
   ```bash
   npm install
   ```
   
4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   # For SQLite
   touch database/database.sqlite
   
   # Or configure MySQL in your .env file
   # DB_CONNECTION=mysql
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=company_manager
   # DB_USERNAME=your_username
   # DB_PASSWORD=your_password
   
   php artisan migrate
   ```

6. **Storage setup**
   ```bash
   php artisan storage:link
   ```

7. **Seed database (optional)**
   
   ```bash
   php artisan db:seed
   ```

## Development

### Running the application

**Option 1: Using Laravel's built-in development command**
```bash
composer run dev
```
This will start the server, queue worker, logs, and Vite development server concurrently.

**Option 2: Manual setup**
```bash
# Terminal 1: Start the Laravel development server
php artisan serve

# Terminal 2: Start the Vite development server
npm run dev

# Terminal 3 (optional): Start the queue worker
php artisan queue:work

# Terminal 4 (optional): Monitor logs
php artisan pail
```

### Using Docker

If you prefer using Docker:

```bash
# Build and start the containers
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate
```

## Usage

1. **Register/Login**: Create an account or log in to access the application
2. **View Companies**: Browse the list of all companies on the main dashboard
3. **Add Company**: Click "Add New Company" to create a company record
4. **Edit Company**: Click on any company to view details or edit information
5. **Upload Logo**: Use the file upload feature to add company logos
6. **Manage Records**: Update or delete company information as needed

## Project Structure

```
company-manager/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php      # Authentication logic
│   │   └── CompanyController.php   # Company CRUD operations
│   └── Models/
│       ├── User.php               # User model
│       └── Company.php            # Company model
├── resources/
│   └── views/
│       ├── auth/                  # Authentication views
│       ├── company/               # Company management views
│       └── layouts/               # Layout templates
├── database/
│   └── migrations/                # Database schema
└── public/
    └── storage/
        └── companies/             # Company logo uploads
```