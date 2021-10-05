# Demo Cart

## Requirements
- PHP 7.4.15
- Laravel Framework 8.61.0

## Installation
1. Clone the rpository: git close REPOSITORY_URL
2. cd into the new folder create
3. Run: 
    ```
    - composer install
    - npm install
    ```
4. Generate a new .env by running: 
    ```
    cp .env.example .env
    ```
6.  Generate the app encryption key
    ```
    php artisan key:generate
    ```
7.  Create a database for your application
8.  In your .env, configure:
    * The database credentials
    ```
    DB_CONNECTION=mysql
    DB_HOST=host
    DB_PORT=port
    DB_DATABASE=databasename
    DB_USERNAME=username
    DB_PASSWORD=password
    ```
    * Email client crdentials
    ```
    MAIL_MAILER=
    MAIL_HOST=
    MAIL_PORT=
    MAIL_USERNAME=
    MAIL_PASSWORD=
    MAIL_ENCRYPTION=
    MAIL_FROM_ADDRESS=
    ```
9. After configuring your database, migrate and seed it
    ```
    - php artisan migrate
    - php artisan db:seed
    ```
10. Start your server
    ```
    php artisan serve
    ```
11. Once the application is up and running, request an API Key from the tab on the menu.
12. You will receive an email with the credentails for the demo API
13. On you.env paste underneath
    ```
    DEMO_API_ID=id
    DEMO_API_KEY=key
    DEMO_API_USER=yourEmailAddress
   
    ```
14. On the app top menu click on Access Cart. Once on the page, use your DEMO_API_KEY to access your cart


## Good luck!
