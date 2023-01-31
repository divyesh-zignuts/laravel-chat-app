## Installation	
1) In order to install this project, clone this repository:
2) composer install
3) php artisan migrate
4) start Project in other terminal with "php artisan serve"
## Event & Listener Setup
1) In order to setup event & listener functionality
2) php artisan make:event PostCreated
3) php artisan make:listener NotifyUser --event=PostCreatd
4) Register event in Event service provider 
    --path: App\Providers\EventServiceProvider
    \App\Events\PostCreated::class => [
            \App\Listeners\NotifyUser::class
        ]
5) php artisan make:mail UserMail
6) create view file in Resources\views directory and add write html code 

## Mail Configration
1) sign-In/sign-up mailtrap https://mailtrap.io/signin 
2) copy mailtrap credentials and past in your .env files
## Make Mail Notification
1) php artisan make:notification NewUserNotification

    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=xxxxxxxxxx
    MAIL_PASSWORD=xxxxxxxxxxxxx
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=xxxxx@gmail.com
    MAIL_FROM_NAME="${APP_NAME}"

## Broadcast Event setup 
1)create new laravel project 

2)install redis server

3)install redis package
composer require predis/predis

4)update redis configration in config/database.php file
'redis' => [
 
    'client' => env('REDIS_CLIENT', 'predis'),
 
    'default' => [
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', 6379),
        'database' => env('REDIS_DB', 0),
    ],
 
    'cache' => [
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', 6379),
        'database' => env('REDIS_CACHE_DB', 1),
    ],
 
],

5)npm install -g laravel-echo-server

6)npm install
7)npm install laravel-echo
8)npm install socket.io-client

9)laravel-echo-server init

10)Run below command in command line
-> redis-server
-> laravel-echo-server start
