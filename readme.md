# Swaggervel Integration with Laravel Passport Example

#### Summary
This project is a companion to [a post]() that guides users through utilizing Laravel Passport with Swaggervel.
The creation was spurred by [this issue](https://github.com/appointer/swaggervel/issues/14) and the pain points I ran into when trying to get this working.

A few steps are highly opinionated and primarily personal preference, like manually segregating versioned Model, Controllers, and Requests.
I've only illustrated the `accessCode` OAuth2 flow but the `password` flow is pretty much identical.
The `implicit` and `application` flows are a little more difficult but we've enabled implicit grants via `Passport::enableImplicitGrant();` if you'd like to test it out.
It's likely possible to have multiple flows working in unison tied to multiple OAuth clients that only support Swaggervel though I haven't tried.

#### Installation
* Clone the repository `git clone git@github.com:w0rd-driven/laravel-passport-swaggervel-example.git`.
* Change to the newly cloned directory `cd laravel-passport-swaggervel-example`
* Run composer install `composer install`.
* Setup the `.env` file `cp .env.example .env`.
* Generate a new key `php artisan key:generate`.
* Setup valet `valet link passport-swaggervel`.
* Secure valet `valet secure passport-swaggervel`.
* Create the database `laravel-passport-swaggervel` as `utf8mb4` in your SQL editor of choice.
* Modify the `.env` variables `DB_USERNAME` and `DB_PASSWORD` to connect to your database.
* If you plan on making changes to the frontend, you'll need to run `npm install` first.

#### Setup
* Register a test user by going to the registration url `https://passport-swaggervel.test/register`.
* Go to the home screen to see the Laravel Passport vue components `https://passport-swaggervel.test/home`.
* Create a new OAuth Client
    * Click `Create New Client`
    * Name `Swaggervel`
    * Redirect URL `https://passport-swaggervel.test/vendor/swaggervel/oauth2-redirect.html`
    * Note: You can modify the redirect url to drop the hostname as just `/vendor/swaggervel/oauth2-redirect.html` but this needs to be changed by accessing the database directly.
    Laravel Passport's validation rules won't allow a url without the full format.
* Copy the `Client ID` and `Secret` and paste into `.env` variables `SWAGGER_CLIENT_ID` and `SWAGGER_CLIENT_SECRET` respectively.
* Go to the Swagger UI endpoint `https://passport-swaggervel.test/api/docs`.
* Click the `Authorize` button with the padlock icon.
* Verify the `client_id` and `client_secret` are correct and click `Authorize`.
* Upon first authorization you should be greeted with the Laravel Passport `Authorization Request` screen. Click `Authorize`.
* Test Swagger by getting the list of users `https://passport-swaggervel.test/api/docs#/users/getUserList`
    * Click `Try it out`
    * Click `Execute`
    * You should see a 200 response with the list of users and the `Curl` section should show a bearer token.

#### Troubleshooting
* If the `Redirect URL` is not correctly pointed to the file at `/vendor/swaggervel/oauth2-redirect.html`, Swagger UI will throw an error and not work properly.

#### Highlighted pain points
* Swaggervel utilizes a file called `oauth2-redirect.html` to capture the expected tokens.
* Laravel Passport's validation rules do not allow the easiest form of pointing to this file with a Redirect URL of `/vendor/swaggervel/oauth2-redirect.html`.
    * You can always use the fully qualified hostname like `https://passport-swaggervel.test/vendor/swaggervel/oauth2-redirect.html` but this has to change any time this jumps to a new server.
* In the Swagger UI blade view, the parameter `oauth2RedirectUrl: '/vendor/swaggervel/oauth2-redirect.html',` is absolutely required to function.
* You're storing the `client_id` and `client_secret` in `config/swaggervel.php` which may be a security concern.
    * I've added the environment variables `SWAGGER_CLIENT_ID` `SWAGGER_CLIENT_SECRET` to keep the contents out of source control.
