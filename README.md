# Blog API (Laravel project)

## Requirements

- PHP 8.0 or higher
- MySQL or MariaDB
- Composer 2.8 or higher

## Installation

### 1. Install Dependencies

```bash
composer install
```

### 2. Environment Setup

```bash
cp .env.example .env
```
Edit the .env file to configure your environment variables (database, app key, etc.):

```bash
php artisan key:generate
```

### 3. Database Setup

1) Connect to MySQL server:

```bash
mysql -u root -p
```

2) Create the database:

```bash
CREATE DATABASE blog;
```

3) Run the import command:

```bash
mysql -u root -p blog < dump.sql
```

### 4. Migrate database and seed

```bash
php artisan migrate
```

```bash
php artisan db:seed
```

### 5. Start Local Server

```bash
php artisan serve
```

Project will be accessible at:
http://localhost:8000

## Testing

### 1. Set test database at .env.testing
### 2. Use command below
```bash
./vendor/bin/pest 
```

