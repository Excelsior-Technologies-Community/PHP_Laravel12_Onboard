# PHP_Laravel12_Onboard


## Project Description:

The project allows users to:

Register and login using Laravel Breeze authentication.

Complete their profile by adding phone number and address.

Create their first post to progress through onboarding steps.

Track onboarding progress on the dashboard with a progress bar and step indicators.

This project provides a ready-to-use template for user onboarding, combining authentication, database relationships, and UI components using Blade templates.


## Key Features:

- Laravel 12 based application.

- User authentication with Laravel Breeze.

- Profile management with a one-to-one relation.

- Onboarding steps using Spatie Onboard package.

- Post creation functionality.

- Dashboard shows progress with completion status.

- Responsive and user-friendly Blade templates.


## Technology & Tools Used

- Backend Framework: Laravel 12 (PHP 8.2+)

- Authentication: Laravel Breeze

- Onboarding Management: Spatie Laravel Onboard Package

- Database: MySQL / MariaDB

- Frontend: Blade Templates, HTML5, CSS3

- JavaScript Tools: npm, Vite (for compiling assets)

- Web Server: PHP Built-in Server (php artisan serve) / XAMPP for local development

- Version Control: Git (optional)

- Package Management: Composer (PHP), npm (JS/CSS assets)



---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Onboard "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Onboard

```

#### Explanation:

Installs a fresh Laravel 12 project and navigates into the project folder.





## STEP 2: Database Setup 

### Open .env and set:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Onboard
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_Onboard

```

#### Explanation:

Connects the Laravel application to MySQL for storing user, profile, and post data.




## STEP 3: Install Laravel Breeze

### Run:

```
composer require laravel/breeze --dev

php artisan breeze:install

npm install

npm run dev

php artisan migrate

```

#### Explanation:

Sets up authentication scaffolding with login, registration, dashboard, and logout features.



## STEP 4: Install Spatie Laravel Onboard Package

### Run command:

```
composer require spatie/laravel-onboard

```

#### Explanation:

Adds onboarding management system to track user progress for profile completion and posts





## STEP 5: Configure User Model

### Open: app/Models/User.php

#### Update:

```
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

use Spatie\Onboard\Concerns\GetsOnboarded;
use Spatie\Onboard\Concerns\Onboardable;

class User extends Authenticatable implements Onboardable
{
    use HasFactory, Notifiable, GetsOnboarded;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}

```

#### Explanation:

Integrates the onboarding package into the User model to track onboarding steps.





## STEP 6: Create Profile Migration

### Command:

```
php artisan make:model Profile -m

```

### Open migration: database/migrations/xxxx_create_profiles_table.php: 

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};

```


### Then Run:

```
php artisan migrate

```


#### Explanation:

Creates a profiles table to store phone, address, and onboarding completion status.





## STEP 7: Create Profile Model Relation

### Open: app/Models/Profile.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'completed'
    ];

    public function isComplete()
    {
        return $this->completed;
    }
}

```

### Open User.php and add relation:

```
public function profile()
{
    return $this->hasOne(Profile::class);
}

```

#### Explanation:

Defines one-to-one relationship between User and Profile for easy access.





## STEP 8: onfigure Onboarding Steps

### Open: app/Providers/AppServiceProvider.php

#### Update boot method:

```
use Spatie\Onboard\Facades\Onboard;
use App\Models\User;

 public function boot(): void
    {
        Onboard::addStep('Complete Profile')
            ->link('/profile')
            ->cta('Complete Profile')
            ->completeIf(function (User $model) {
                return optional($model->profile)->completed == true;
            });

        Onboard::addStep('Create First Post')
            ->link('/post/create')
            ->cta('Create Post')
            ->completeIf(function (User $user) {
                return $user->posts()->exists(); // ✅ Mark complete if user has any posts
            });
    }

```

#### Explanation:

Adds onboarding steps to track if the user has completed profile and created first post.





## STEP 9: Create Profile Controller

### Run:

```
php artisan make:controller ProfileController

php artisan make:controller PostController

```

### Open: app/Http/Controllers/ProfileController.php

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function store(Request $request)
    {
        Profile::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'phone' => $request->phone,
                'address' => $request->address,
                'completed' => true
            ]
        );

        return redirect('/dashboard');
    }
}


```

### Open: app/Http/Controllers/PostController.php

```

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create()
    {
        return view('post.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        $user = auth()->user();

        // Create the post
        $user->posts()->create([
            'title' => $request->title,
            'body'  => $request->body,
        ]);

        // ✅ No need to call Spatie complete() here
        // Progress bar now calculates completed steps in Blade

        return redirect('/dashboard')->with('success', 'Post created!');
    }
}

```




## STEP 10: Create Migration 

### Run:

```
php artisan make:migration create_posts_table --create=posts

php artisan make:migration add_completed_to_profile_table

```

### Edit the migration (database/migrations/xxxx_create_posts_table.php):

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

```


### database/migrations/xxxxadd_completed_to_profile_table

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
    if (!Schema::hasColumn('profiles', 'completed')) {
        $table->boolean('completed')->default(false);
    }
});
    }

    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('completed');
        });
    }
};


```

#### Explanation:

Creates posts table and ensures profile completion tracking column exists.





## STEP 11: Create Routes

### Open: routes/web.php

```

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth'])->group(function () {

    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/post', [PostController::class, 'store'])->name('post.store');

});

Route::get('/profile', [ProfileController::class, 'index'])
    ->name('profile.edit');   // ✅ Named route

Route::post('/profile', [ProfileController::class, 'store'])
    ->name('profile.update');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';


```

#### Explanation:

Defines routes for profile editing, post creation, and dashboard with authentication protection.





## STEP 12: Create Views Blade Template

### Create: resources/views/profile.blade.php

```
<x-app-layout>

<style>
body{
    background:#f4f6f9;
    font-family:Arial, Helvetica, sans-serif;
}

.container{
    max-width:500px;
    margin:40px auto;
}

.card{
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
}

.title{
    font-size:24px;
    font-weight:bold;
    margin-bottom:20px;
    color:#333;
    text-align:center;
}

.input-group{
    margin-bottom:20px;
}

.input-label{
    display:block;
    margin-bottom:5px;
    font-weight:bold;
    color:#555;
}

.input-field{
    width:100%;
    padding:12px;
    border-radius:8px;
    border:1px solid #ccc;
    font-size:16px;
}

.input-field:focus{
    border-color:#4CAF50;
    outline:none;
}

.btn{
    width:100%;
    padding:12px;
    background:#4CAF50;
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}

.btn:hover{
    background:#45a049;
}
</style>

<div class="container">

<div class="card">

<div class="title">
Complete Your Profile
</div>

<form method="POST" action="/profile">

@csrf

<div class="input-group">
<label class="input-label">Phone Number</label>
<input type="text" name="phone" class="input-field" placeholder="Enter phone number" required>
</div>

<div class="input-group">
<label class="input-label">Address</label>
<input type="text" name="address" class="input-field" placeholder="Enter address" required>
</div>

<button type="submit" class="btn">
Save Profile
</button>

</form>

</div>

</div>

</x-app-layout>

```



### resources/views/dashboard.blade.php

```
<x-app-layout>

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .step {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
        }

        .step-complete {
            background: #e8f5e9;
            border-color: #4CAF50;
        }

        .step-pending {
            background: #fff3e0;
            border-color: #ff9800;
        }

        .step-title {
            font-size: 18px;
        }

        .btn {
            padding: 8px 15px;
            background: #2196F3;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn:hover {
            background: #1976D2;
        }

        .progress-bar {
            width: 100%;
            height: 20px;
            background: #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .progress {
            height: 100%;
            background: #4CAF50;
            border-radius: 10px;
            text-align: center;
            color: white;
            font-size: 12px;
        }

        .complete {
            color: #4CAF50;
            font-weight: bold;
        }

        .pending {
            color: #ff9800;
            font-weight: bold;
        }
    </style>

    <div class="container">
        <div class="card">
            <div class="title">Dashboard Onboarding</div>

            @php
                $user = auth()->user()->fresh();

                // Define steps manually with completion check
                $steps = [
                    [
                        'title' => 'Complete Profile',
                        'link' => '/profile',
                        'cta' => 'Complete Profile',
                        'completed' => optional($user->profile)->completed ?? false,
                    ],
                    [
                        'title' => 'Create First Post',
                        'link' => '/post/create',
                        'cta' => 'Create Post',
                        'completed' => $user->posts()->exists(),
                    ],
                ];

                $total = count($steps);
                $completed = collect($steps)->filter(fn($step) => $step['completed'])->count();
                $percent = $total > 0 ? ($completed / $total) * 100 : 0;
            @endphp

            <div class="progress-bar">
                <div class="progress" style="width: {{ round($percent) }}%">
                    {{ round($percent) }}%
                </div>
            </div>

            @foreach($steps as $step)
                <div class="step {{ $step['completed'] ? 'step-complete' : 'step-pending' }}">
                    <div class="step-title">
                        @if($step['completed'])
                            <span class="complete">✅ {{ $step['title'] }}</span>
                        @else
                            <span class="pending">⬜ {{ $step['title'] }}</span>
                        @endif
                    </div>
                    @if(!$step['completed'])
                        <a href="{{ $step['link'] }}" class="btn">{{ $step['cta'] }}</a>
                    @endif
                </div>
            @endforeach

            @if($completed === $total)
                <div class="complete" style="margin-top:20px;font-size:18px;">
                    🎉 Onboarding Completed Successfully!
                </div>
            @endif
        </div>
    </div>

</x-app-layout>
```



### resources/views/post/create.blade.php

```
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Your First Post</title>

<style>
    body {
        background: #f4f6f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .card {
        background: #ffffff;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .title {
        font-size: 28px;
        font-weight: 700;
        color: #222;
        text-align: center;
        margin-bottom: 10px;
    }

    .subtitle {
        font-size: 16px;
        color: #666;
        text-align: center;
        margin-bottom: 30px;
    }

    .input-group {
        margin-bottom: 25px;
    }

    .input-label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #555;
    }

    .input-field, textarea {
        width: 100%;
        padding: 14px 16px;
        border-radius: 10px;
        border: 1px solid #ccc;
        font-size: 16px;
        transition: all 0.3s;
    }

    .input-field:focus, textarea:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        outline: none;
    }

    textarea {
        resize: vertical;
    }

    .btn {
        width: 100%;
        padding: 15px;
        background: #4CAF50;
        color: white;
        font-size: 18px;
        font-weight: 600;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s;
    }

    .btn:hover {
        background: #45a049;
        transform: translateY(-2px);
    }

    .back-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        font-weight: 500;
        color: #4CAF50;
        text-decoration: none;
        transition: color 0.3s;
    }

    .back-link:hover {
        color: #388E3C;
    }

    @media (max-width: 640px) {
        .card {
            padding: 30px 20px;
        }
        .title {
            font-size: 24px;
        }
        .btn {
            font-size: 16px;
        }
    }
</style>
</head>
<body>

@if(session('error'))
    <div style="color:red; margin-bottom:15px;">
        Error: {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div style="color:red; margin-bottom:15px;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container">
    <div class="card">
        <div class="title">Create Your First Post</div>
        <div class="subtitle">Fill in the details below to share your first post.</div>

        <form method="POST" action="/post">
            @csrf

            <div class="input-group">
                <label class="input-label" for="title">Post Title</label>
                <input type="text" name="title" id="title" class="input-field" placeholder="Enter post title" required>
            </div>

            <div class="input-group">
                <label class="input-label" for="body">Post Content</label>
                <textarea name="body" id="body" rows="6" class="input-field" placeholder="Write your post here..." required></textarea>
            </div>

            <button type="submit" class="btn">Save Post</button>
        </form>

        <a href="{{ url('/dashboard') }}" class="back-link">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>

```

#### Explanation:

Provides UI for completing profile, creating posts, and tracking onboarding progress.





## STEP 13:  Test in Browser

### Run: 

```
php artisan serve

```

### Open:

```
http://127.0.0.1:8000

```

#### Explanation:

Starts the development server and tests the application in the browser.




## So you can see this type Output:


### Register Page:


<img width="1919" height="943" alt="Screenshot 2026-02-25 163731" src="https://github.com/user-attachments/assets/d2354163-ea53-4e67-ac2a-252bdbe95148" />


### Dashboard Page:


<img width="1910" height="750" alt="Screenshot 2026-02-25 190034" src="https://github.com/user-attachments/assets/f32a323b-f7ad-4e66-ad25-8c3ce7a059ab" />


### Create Profile Page:


<img width="1919" height="912" alt="Screenshot 2026-02-25 165654" src="https://github.com/user-attachments/assets/c07ab869-3064-4966-8a1f-f42f6a45899a" />


#### after create profile:


<img width="1919" height="863" alt="Screenshot 2026-02-25 165703" src="https://github.com/user-attachments/assets/87972e57-f236-43cd-a2ba-8f531e426360" />


### Create Post Page:


<img width="1919" height="942" alt="Screenshot 2026-02-25 170614" src="https://github.com/user-attachments/assets/8e9b76e1-eb20-45cc-962f-929107ca83a7" />


### After create Post show:


<img width="1919" height="958" alt="Screenshot 2026-02-25 183355" src="https://github.com/user-attachments/assets/54916cc7-45cb-4a1e-869a-a593e864821a" />




--- 

# Project Folder Structure:

```
PHP_Laravel12_Onboard/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ProfileController.php
│   │   │   └── PostController.php
│   │   ├── Middleware/
│   │   └── Kernel.php
│   ├── Models/
│   │   ├── Profile.php
│   │   ├── User.php
│   │   └── Post.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── ...
├── bootstrap/
├── config/
│   ├── app.php
│   ├── database.php
│   └── onboard.php (if published)
├── database/
│   ├── factories/
│   ├── migrations/
│   │   ├── xxxx_create_profiles_table.php
│   │   ├── xxxx_create_posts_table.php
│   │   └── xxxx_add_completed_to_profiles_table.php
│   └── seeders/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   ├── views/
│   │   ├── dashboard.blade.php
│   │   ├── profile.blade.php
│   │   ├── post/
│   │   │   └── create.blade.php
│   │   ├── profile/
│   │   │   └── partials/
│   │   │       └── update-profile-information-form.blade.php (optional)
│   │   └── layouts/
│   │       └── app.blade.php (from Breeze)
├── routes/
│   └── web.php
├── storage/
├── tests/
├── vendor/
├── .env
├── composer.json
└── package.json

```
