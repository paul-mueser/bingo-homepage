# Bingo Homepage

## Project setup

Run the following command in the project root to install the required Node.js dependencies:

```
npm install
```

## Developing and testing

### Setup Docker environment

Create a folder named `db` in the project root with an `init.sql` file to initialize the database.
Setup a `secrets.php` file in the `api` folder with the following content:

```php
<?php
    $secret_key = 'your_secret_key_here';
    $DB_HOST = 'db';
    $DB_USER = 'devuser';
    $DB_PASS = 'devpass';
    $DB_NAME = 'bingo_dev';
    $CORRECT_AUTH_CODE = 'correct_auth_code_here';
    $site_url = 'http://localhost:8081';
?>
```

Where `your_secret_key_here` is an JWT secret key of your choice, and `correct_auth_code_here` is the authentication code for account creation.
Next, setup a file named `.htaccess` in the `api` folder with the following content:

```
RewriteEngine On

# If the requested file exists, serve it
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Otherwise, route to the correct PHP file
RewriteRule ^api/([a-zA-Z0-9_-]+)$ $1.php [L]
```

**Important:** Ensure that the `db` folder and `api/.htaccess` and `api/secrets.php` file are included in your `.gitignore` to prevent sensitive information from being committed to version control.\
Also note that the values you provided in `secrets.php` should match those in the `docker-compose.yml` file for the database service.

**Please don't use sensitive values in development; these are just for local testing purposes. Always change them in production.**

### Run Docker containers

Run the following command in the project root to build and start the Docker containers:
```
docker-compose up --build -d
```
This will start the PHP server on port `8000` and the MySQL database on port `3306`, as well as a phpMyAdmin interface on port `8081`.

### Run the frontend with hot-reloading

Run the following command in the project root to start the Vue.js development server:

```
npm run serve
```

### Access the application

- Frontend: `http://localhost:8080`
- phpMyAdmin: `http://localhost:8081` (Username: `devuser`, Password: `devpass`)

## Build for production

Run the following command in the project root to build the Vue.js application for production:

```
npm run build
```

This will create a `dist` folder with the production-ready files. You can then serve these files using a web server of your choice.

**Important:** Don't forget to change the database credentials and secret keys in `secrets.php` before deploying to production and don't push sensitive information to version control!
