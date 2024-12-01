

### **Step 1: Install Laravel**
First, we need to set up a new Laravel project. To do this, run the following command (make sure Composer is installed):

```bash
composer create-project --prefer-dist laravel/laravel laravel-tasks-app
```

Once the installation is complete, navigate to the project directory:

```bash
cd laravel-tasks-app
```

### **Step 2: Set Up SQLite Database**
We'll use an SQLite database for simplicity.

1. **Create the SQLite file:**

   In the root directory of your project, create the database file:

   ```bash
   touch database/database.sqlite
   ```

2. **Update `.env` to configure SQLite:**

   In the `.env` file, change the database configuration to use SQLite:

   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=/absolute/path/to/your/laravel-tasks-app/database/database.sqlite
   ```

   Make sure to provide the correct absolute path to the `database.sqlite` file.

---

### **Step 3: Set Up Database Migrations for Authentication and Tasks**

We'll need two tables: `users` for authentication and `tasks` for managing the tasks.

#### **1. User Table**

In Laravel, we can modify the default `users` table migration file, which is located in the `database/migrations/xxxx_xx_xx_create_users_table.php`.

We'll add fields like `name`, `email`, `password`, and timestamps.

```php
public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->timestamps();
    });
}
```

#### **2. Task Table**

Now, create a migration file for tasks:

```bash
php artisan make:migration create_tasks_table
```

In the migration file `create_tasks_table.php`, define the schema for the `tasks` table:

```php
public function up()
{
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->boolean('completed')->default(false);
        $table->unsignedBigInteger('user_id');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->timestamps();
    });
}
```

- **`title`**: Title of the task.
- **`description`**: Optional description of the task.
- **`completed`**: Boolean to indicate if the task is done.
- **`user_id`**: Foreign key to associate the task with a user.

Now, run the migrations:

```bash
php artisan migrate
```

---

### **Step 4: Create the User Model**
Since Laravel provides a basic `User` model in `app/Models/User.php`, we will use that.

Make sure the `fillable` property is defined to allow mass assignment:

```php
protected $fillable = ['name', 'email', 'password'];
```

---

### **Step 5: Create the Task Model**
We will create a Task model that will handle the task records in the database.

```bash
php artisan make:model Task
```

In `app/Models/Task.php`, define the relationship between tasks and users:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'completed', 'user_id'];

    // Each task belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

### **Step 6: Create Custom Authentication**
We will create the routes, controllers, and views for custom authentication from scratch.

#### **1. Routes for Authentication**
In `routes/web.php`, define routes for login, register, and logout:

```php
// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
```

#### **2. AuthController**
Now, create a controller for handling authentication:

```bash
php artisan make:controller AuthController
```

In `app/Http/Controllers/AuthController.php`, add the methods to handle authentication:

```php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('tasks');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    // Show registration form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/tasks');
    }

    // Handle logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
```

- **`showLoginForm`**: Displays the login form.
- **`login`**: Authenticates the user.
- **`showRegistrationForm`**: Displays the registration form.
- **`register`**: Registers a new user and logs them in.
- **`logout`**: Logs out the user.

#### **3. Middleware for Authenticated Routes**
Now we will protect the routes related to task management. For that, we will apply a middleware.

In `routes/web.php`, protect the routes using the `auth` middleware:

```php
Route::middleware('auth')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::get('/tasks/create', [TaskController::class, 'create']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
});
```

Now, only authenticated users can access task routes.

---

### **Step 7: Create Task Management**
We will create a `TaskController` to handle CRUD operations for tasks.

#### **1. TaskController**
Run the following command to create the controller:

```bash
php artisan make:controller TaskController
```

In `app/Http/Controllers/TaskController.php`, add the following methods for creating, reading, updating, and deleting tasks:

```php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // List all tasks for the authenticated user
    public function index()
    {
        $tasks = Auth::user()->tasks;
        return view('tasks.index', compact('tasks'));
    }

    // Show the form to create a new task
    public function create()
    {
        return view('tasks.create');
    }

    // Store a new task
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('tasks');
    }

    // Show the form to edit an existing task
    public function edit(Task $task)
    {
        if ($task->user_id != Auth::id()) {
            abort(403);
        }
        return view('tasks.edit', compact('task'));
    }

    // Update an existing task
    public function update(Request $request, Task $task)
    {
        if ($task->user_id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks');
    }

    // Delete a task
    public function destroy(Task $task)
    {
        if ($task->user_id != Auth::id()) {
            abort(403);
        }

        $task->delete();
        return redirect()->route('

tasks');
    }
}
```

- **`index`**: Lists all tasks belonging to the logged-in user.
- **`create`**: Shows the form to create a new task.
- **`store`**: Saves the task to the database.
- **`edit`**: Shows the form to edit an existing task.
- **`update`**: Updates the task.
- **`destroy`**: Deletes the task.

---

### **Step 8: Views for Authentication and Tasks**

1. **Login Form (resources/views/auth/login.blade.php):**

```html
<form action="/login" method="POST">
    @csrf
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <button type="submit">Login</button>
    </div>
</form>
<a href="/register">Register</a>
```

2. **Registration Form (resources/views/auth/register.blade.php):**

```html
<form action="/register" method="POST">
    @csrf
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
    </div>
    <div>
        <button type="submit">Register</button>
    </div>
</form>
<a href="/login">Login</a>
```

3. **Task Listing (resources/views/tasks/index.blade.php):**

```html
<h1>Your Tasks</h1>
<a href="/tasks/create">Create New Task</a>
<ul>
    @foreach ($tasks as $task)
        <li>
            <a href="/tasks/{{ $task->id }}/edit">{{ $task->title }}</a>
            <form action="/tasks/{{ $task->id }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </li>
    @endforeach
</ul>
```

4. **Create Task Form (resources/views/tasks/create.blade.php):**

```html
<h1>Create Task</h1>
<form action="/tasks" method="POST">
    @csrf
    <div>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
    </div>
    <div>
        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea>
    </div>
    <div>
        <button type="submit">Create Task</button>
    </div>
</form>
<a href="/tasks">Back to Task List</a>
```

5. **Edit Task Form (resources/views/tasks/edit.blade.php):**

```html
<h1>Edit Task</h1>
<form action="/tasks/{{ $task->id }}" method="POST">
    @csrf
    @method('PUT')
    <div>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="{{ $task->title }}" required>
    </div>
    <div>
        <label for="description">Description:</label>
        <textarea id="description" name="description">{{ $task->description }}</textarea>
    </div>
    <div>
        <button type="submit">Update Task</button>
    </div>
</form>
<a href="/tasks">Back to Task List</a>
```

---

### **Step 9: Running the Application**

1. Make sure all your migrations are up to date:

```bash
php artisan migrate
```

2. Start the development server:

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` to see your Laravel app in action!

---

### **Conclusion**

You've now built a complete Laravel application from scratch, including custom authentication, task management with CRUD operations, and protected routes using middleware.

If you have any questions about specific parts of the code or how Laravel works under the hood, feel free to ask!
