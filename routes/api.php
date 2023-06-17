<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Bookmark;
use App\Models\Exercise;
use App\Models\History;
use App\Models\UserLearnExercise;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// users
// https://vocab-master-api.000webhostapp.com/api/users
Route::get('/users', function() {
    return User::all();
});

Route::get('/users/{id}', function($id) {
    $data = User::findOrFail($id);
    return $data;
});

Route::get('/users/email/{email}', function($email) {
    return DB::table('users')->where('email', $email)->get();
});

Route::post('/users/add', function(Request $request) {
    $user = User::where('email', $request->input('email'))->first();
    
    if ($user) {
        return;
    }
    $data = User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => $request->input('password'),
    ]);
    return 'Success';
});

Route::post('/auth/login', function (Request $request) {
    $user = User::where('email', $request->input('email'))->where('password', $request->input('password'))->first();
    
    if ($user) {
        $token = Str::random(60);
        
        return response()->json([
            'user' => $user,
            'token' => hash('sha256', $token),
            'message' => 'Login successful',
        ]);
    }

    return response()->json([
        'message' => 'Invalid login credentials',
    ], 401);
});

Route::post('/users/update/{id}', function(Request $request, $id) {
    $data = User::findOrFail($id);
    $data->update($request->all());
});

Route::get('/users/delete/{id}', function($id) {
    return User::destroy($id);
});


// bookmarks
Route::get('/bookmarks', function() {
    return Bookmark::all();
});

Route::get('/bookmarks/{id}', function($id) {
    $data = Bookmark::findOrFail($id);
    return $data;
});

Route::get('/bookmarks/user/{id}', function($id) {
    $data = Bookmark::where('id_user', $id)->get();
    return $data;
});

Route::get('/bookmarks/user/{id}/{word}', function($id, $word) {
    $data = Bookmark::where('id_user', $id)->where('word', $word)->get();
    return $data;
});


Route::post('/bookmarks/add', function(Request $request) {
    $bm = Bookmark::where('id_user', $request->input('id_user'))->where('word', $request->input('word'))->first();
    if ($bm) {
        return;
    }
    $data = Bookmark::create([
        'id_user' => $request->input('id_user'),
        'word' => $request->input('word'),
    ]);
});

Route::post('/bookmarks/update/{id}', function(Request $request, $id) {
    $data = Bookmark::findOrFail($id);
    $data->update($request->all());
});

Route::get('/bookmarks/delete/{id}', function($id) {
    return Bookmark::destroy($id);
});



// historys
Route::get('/historys', function() {
    return History::all();
});

Route::get('/historys/{id}', function($id) {
    $data = History::findOrFail($id);
    return $data;
});

Route::get('/historys/user/{id}', function($id) {
    $data = History::where('id_user', $id)->get();
    return $data;
});

Route::post('/historys/add', function(Request $request) {
    $h = History::where('id_user', $request->input('id_user'))->where('word', $request->input('word'))->first();
    if ($h) {
        return;
    }
    $data = History::create([
        'id_user' => $request->input('id_user'),
        'word' => $request->input('word'),
    ]);
});

Route::post('/historys/update/{id}', function(Request $request, $id) {
    $data = History::findOrFail($id);
    $data->update($request->all());
});

Route::get('/historys/delete/{id}', function($id) {
    return History::destroy($id);
});


// exercises
Route::get('/exercises', function() {
    return Exercise::all();
});

Route::get('/exercises/{id}', function($id) {
    $data = Exercise::findOrFail($id);
    return $data;
});

Route::post('/exercises/add', function(Request $request) {
    $data = Exercise::create([
        'name_exercise' => $request->input('name_exercise'),
    ]);
});

Route::post('/exercises/update/{id}', function(Request $request, $id) {
    $data = Exercise::findOrFail($id);
    $data->update($request->all());
});

Route::get('/exercises/delete/{id}', function($id) {
    return Exercise::destroy($id);
});


// user_learn_exercise
Route::get('/user_learn_exercise', function() {
    return UserLearnExercise::all();
});

Route::get('/user_learn_exercise/{id}', function($id) {
    $data = UserLearnExercise::findOrFail($id);
    return $data;
});

Route::post('/user_learn_exercise/add', function(Request $request) {
    $data = UserLearnExercise::create([
        'id_user' => $request->input('id_user'),
        'id_exercise' => $request->input('id_exercise'),
    ]);
});

Route::post('/user_learn_exercise/update/{id}', function(Request $request, $id) {
    $data = UserLearnExercise::findOrFail($id);
    $data->update($request->all());
});

Route::get('/user_learn_exercise/delete/{id}', function($id) {
    return UserLearnExercise::destroy($id);
});


// dictionary - tra nghĩa của từ
// https://vocab-master-api.000webhostapp.com/api/dictionary/cat
Route::get('/dictionary/{word}', function($word) {
    $client = new Client();
    $response = $client->get('https://api.dictionaryapi.dev/api/v2/entries/en/'.$word);
    $data = json_decode($response->getBody(), true);
    return $data;
    
});


// translate - dịch nghĩa
// nếu dịch từ tiếng Anh sang tiếng Việt -> target = vi
// nếu dịch từ tiếng Việt sang tiếng Anh -> target = en
// http://127.0.0.1:8000/api/translate/hello guys/vi
Route::get('/translate/{word}/{target}', function($word, $target) {
    $client = new Client();
    $response = $client->get('https://translation.googleapis.com/language/translate/v2?key=AIzaSyDLj0iPsxyG9EDNAMTZ-MX_CD5jkZc4gN0&q='.$word.'&target='.$target);
    $data = json_decode($response->getBody(), true);

    return $data;
});


// vocabulary
Route::get('/vocabulary', function() {
    $data = json_decode(file_get_contents(storage_path('app/wordlist.json')), true);
    return response()->json($data);
});