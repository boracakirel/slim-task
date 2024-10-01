# SETTING UP LOCAL ENVIRONMENT

## Requirements:
- PHP 8.3+ (Simplexml extension)
- MariaDB 15.1
## Setup
- Run the commands in terminal to start the server:
```
cd public/
php -S localhost:8888
```
- Application base URL: http://localhost:8888
- Run the command below. This will create a database and tables, and populate the contents of the tables.
```
php data-seeder.php
```
