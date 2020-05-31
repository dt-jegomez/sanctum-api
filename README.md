<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.


## Getting Started

### Step 1: setup database in .env file

    DB_DATABASE=api-sanctum
    DB_USERNAME=root
    DB_PASSWORD=password

### Step 2:Install Laravel Sanctum.

    composer require laravel/sanctum
    
### Step 3:Publish the Sanctum configuration and migration files.
    
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    
### Step 4:Run your database migrations.

    php artisan migrate

### Step 5:Add the Sanctum's middleware.

    use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
    
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            EnsureFrontendRequestsAreStateful::class,
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];
 
 
### Step 6:To use tokens for users.

    use Laravel\Sanctum\HasApiTokens;

    class User extends Authenticatable
    {
        use HasApiTokens, Notifiable;
    }
    
### Step 7:Let's create the seeder for the User model, Now let's insert as record and seed users table with user

     
    7.1 php artisan make:seeder UsersTableSeeder
    
    7.2 DB::table('users')->insert([
        'name' => 'John Doe',
        'email' => 'john@doe.com',
        'password' => Hash::make('password')    
    ]);
    
    7.3 seed users table with user
    
 ### Step 8: create a controller: LoginController
 
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\User;
    use Illuminate\Support\Facades\Hash;

    class LoginController extends Controller
    {
        public function auth(Request $request)
        {
            $user= User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([ 'message' => ['These credentials do not match our records.'] ], 404);
            }

            $token = $user->createToken('my-app-token')->plainTextToken;

            $response = [ 'user' => $user, 'token' => $token ];

            return response($response, 201);
        }
    }

    
 ### Step 9: login route in the routes/api.php file:  
 
    Route::group(['middleware' => 'guest:api'], function () {
        Route::post('login', 'LoginController@auth');
    });

    Route::group(['middleware' => 'auth:sanctum'], function () {

    });
    
### Test with postman, Result will be below    

Post: http://localhost:8000/api/login

{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@doe.com",
        "email_verified_at": null,
        "created_at": null,
        "updated_at": null
    },
    "token": "1|qFpxt1XND5Ac0HoTlndfkVxz9vc3MGf1GFuHsCteCvFNP8KU5UnQm9GK94tuN2gVdHwG3SiYhQOwYIMf"
}
    
    
## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
