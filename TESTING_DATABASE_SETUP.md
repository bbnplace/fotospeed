# Testing Database Setup

Your tests are now configured to use a **separate database** that will not affect your development data.

## Current Configuration (MySQL)

Tests are configured to use a **separate MySQL database** (`fotospeed_testing`) that:
- ✅ Is completely isolated from your development database (`flow`)
- ✅ Uses the same credentials as your development environment
- ✅ Supports all MySQL-specific features
- ✅ Is automatically refreshed for each test run

## Running Tests

```bash
# Run all tests
php artisan test

# Or use PHPUnit directly
vendor/bin/phpunit

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run with coverage
php artisan test --coverage
```

## Alternative: Use Separate MySQL Database

If you need to test MySQL-specific features:

### 1. Create the test database:
```bash
mysql -u root -p
CREATE DATABASE fotospeed_testing;
exit;
```

### 2. Update `.env.testing`:
```env
# Comment out SQLite:
# DB_CONNECTION=sqlite
# DB_DATABASE=:memory:

# Uncomment MySQL:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fotospeed_testing
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Run migrations on test database:
```bash
php artisan migrate --env=testing
```

## How It Works

- `phpunit.xml` sets `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:`
- `.env.testing` provides additional environment overrides
- `RefreshDatabase` trait in `TestCase.php` automatically migrates and resets the database before each test
- Your development `.env` file remains unchanged

## Safety Features

✅ Tests never touch your development database  
✅ Each test gets a fresh database state  
✅ Database is automatically cleaned up after tests  
✅ No manual database management required
