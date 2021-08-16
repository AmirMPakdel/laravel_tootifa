<?php

use App\Http\Middleware\EnsureStudentTokenIsValid;
use App\Http\Middleware\EnsureUserTokenIsValid;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByRequestData;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//********************************************************MAIN***********************************************************

// Main routes
Route::group([
    'prefix' => 'api/main',
    'namespace'  => 'App\Http\Controllers\API\Main',
], function () {
    Route::post('/user/checkphonenumber', 'UserRegistrationController@checkPhoneNumber');
    Route::post('/user/login', 'UserRegistrationController@loginWithPassword');
    Route::post('/user/verificationcode/send', 'UserRegistrationController@sendVerificationCode');
    Route::post('/user/verificationcode/check', 'UserRegistrationController@checkVerificationCode');
    Route::post('/user/register', 'UserRegistrationController@completeRegistration');
});

//********************************************************TENANT*********************************************************

// Tenant users' routes (admin)
Route::group([
    'prefix' => 'api/tenant/user',
    'namespace'  => 'App\Http\Controllers\API\Admin',
    'middleware' => [ EnsureUserTokenIsValid::class, InitializeTenancyByRequestData::class],
], function () {
    Route::post('/courses/create', 'Courses\CoursesController@createCourse');
    Route::post('/courses/fetch', 'Courses\CoursesController@fetchCourses');
    Route::post('/courses/fetch/specific', 'Courses\CoursesController@fetchSpecificCourses');
    Route::post('/course/load', 'Courses\CoursesController@loadCourse');
    Route::post('/course/edit/{ep}', 'Courses\CourseEditController@editCourse');

    Route::post('/course/students/fetch', 'Courses\CourseStudentController@fetchCourseStudents');
    Route::post('/course/students/add', 'Courses\CourseStudentController@addCourseStudent');
    Route::post('/course/students/remove', 'Courses\CourseStudentController@removeCourseStudents');
    Route::post('/course/students/changeaccess', 'Courses\CourseStudentController@changeCourseStudentsAccess');
    Route::post('/course/students/importexcel', 'Courses\CourseStudentController@importCourseStudentsExcel');
    Route::post('/course/students/exportexcel', 'Courses\CourseStudentController@exportCourseStudentsExcel');

    Route::post('/posts/create', 'Posts\PostsController@createPost');
    Route::post('/posts/fetch', 'Posts\PostsController@fetchPosts');
    Route::post('/posts/fetch/specific', 'Posts\PostsController@fetchSpecificPosts');
    Route::post('/post/load', 'Posts\PostsController@loadPost');
    Route::post('/post/edit/{ep}', 'Posts\PostEditController@editPost');

    Route::post('/levelonegroups/create', 'GroupsController@createLevelOneGroup');
    Route::post('/leveltwogroups/create', 'GroupsController@createLevelTwoGroup');
    Route::post('/levelthreegroups/create', 'GroupsController@createLevelThreeGroup');
    Route::post('/levelonegroups/edit', 'GroupsController@editLevelOneGroup');
    Route::post('/leveltwogroups/edit', 'GroupsController@editLevelTwoGroup');
    Route::post('/levelthreegroups/edit', 'GroupsController@editLevelThreeGroup');
    Route::post('/levelonegroups/delete', 'GroupsController@deleteLevelOneGroup');
    Route::post('/leveltwogroups/delete', 'GroupsController@deleteLevelTwoGroup');
    Route::post('/levelthreegroups/delete', 'GroupsController@deleteLevelThreeGroup');
    Route::post('/tags/create', 'TagsController@createTag');
    Route::post('/tags/edit', 'TagsController@editTag');
    Route::post('/tags/delete', 'TagsController@deleteTag');

    Route::post('/educators/create', 'EducatorsController@createEducator');
    Route::post('/educators/update', 'EducatorsController@updateEducator');
    Route::post('/educators/image/edit', 'EducatorsController@editEducatorImage');
    Route::post('/educators/delete', 'EducatorsController@deleteEducator');
    Route::post('/educators/fetch', 'EducatorsController@fetchEducators');

    Route::post('/writers/create', 'WritersController@createWriter');
    Route::post('/writers/update', 'WritersController@updateWriter');
    Route::post('/writers/image/edit', 'EducatorsController@editWriterImage');
    Route::post('/writers/delete', 'WritersController@deleteWriter');
    Route::post('/writers/fetch', 'WritersController@fetchWriters');

    Route::post('/mainpage/edit/{ep}', 'MainPage\UserMainPageEditController@editMainPage');
    Route::post('/mainpage/load', 'MainPage\UserMainPageController@loadMainPage');

    Route::post('/course/checkedcomments/fetch/{chunk_count}/{page_count}', 'CommentsController@fetchCourseCheckedComments');
    Route::post('/course/uncheckedcomments/fetch/{chunk_count}/{page_count}', 'CommentsController@fetchCourseUnCheckedComments');
    Route::post('/course/uncheckedcomments/count', 'CommentsController@getCourseUnCheckedCommentsCount');
    Route::post('/comment/delete', 'CommentsController@deleteComment');
    Route::post('/comment/set/checked', 'CommentsController@setCommentChecked');
    Route::post('/comment/set/valid', 'CommentsController@setCommentValid');


});

// Tenant public routes
Route::group([
    'prefix' => 'api/tenant/public',
    'namespace'  => 'App\Http\Controllers\API',
    'middleware' => [ InitializeTenancyByRequestData::class],
], function () {
    Route::get('/writer/{id}/image', 'Admin\EducatorsController@getImage');
    Route::get('/educator/{id}/image', 'Admin\WritersController@getImage');
    Route::get('/course/{id}/logo', 'Admin\Courses\CoursesController@getLogo');
    Route::get('/course/{id}/cover', 'Admin\Courses\CoursesController@getCover');
    Route::get('/post/{id}/logo', 'Admin\Posts\PostsController@getLogo');
    Route::get('/post/{id}/cover', 'Admin\Posts\PostsController@getCover');
    Route::get('/usermainpage/logo', 'Admin\MainPage\UserMainPageController@getPageCover');
    Route::get('/usermainpage/cover', 'Admin\MainPage\UserMainPageController@getPageLogo');
    Route::get('/usermainpage/banner_cover', 'Admin\MainPage\UserMainPageController@getBannerCover');
    Route::get('/groups/fetch', 'Admin\GroupsController@fetchGroups');
    Route::get('/tags/fetch', 'Admin\TagsController@fetchTags');
    Route::get('/categories/fetch', 'Admin\CategoriesController@fetchCategories');
});

// Tenant students' public routes
Route::group([
    'prefix' => 'api/tenant/student/public',
    'namespace'  => 'App\Http\Controllers\API\Student',
    'middleware' => [ InitializeTenancyByRequestData::class],
], function () {
    Route::post('/checkphonenumber', 'StudentRegistrationController@checkPhoneNumber');
    Route::post('/login', 'StudentRegistrationController@loginWithPassword');
    Route::post('/verificationcode/send', 'StudentRegistrationController@sendVerificationCode');
    Route::post('/verificationcode/check', 'StudentRegistrationController@checkVerificationCode');
    Route::post('/register', 'StudentRegistrationController@completeRegistration');
});

// Tenant students' routes
Route::group([
    'prefix' => 'api/tenant/student',
    'namespace'  => 'App\Http\Controllers\API\Student',
    'middleware' => [ EnsureStudentTokenIsValid::class, InitializeTenancyByRequestData::class],
], function () {
    Route::post('/registration/course/complete', 'StudentCourseController@completeCourseRegistration');
    Route::post('/profile/load', 'StudentProfileController@loadStudentProfile');
    Route::post('/profile/update', 'StudentProfileController@updateStudentProfile');
    Route::post('/courses/fetch', 'StudentCourseController@fetchCourses');
    Route::post('/course/load', 'StudentCourseController@loadCourse');
    Route::post('/course/score/get', 'StudentCourseController@getCourseScore');
    Route::post('/course/score/update', 'StudentCourseController@updateCourseScore');
    Route::post('/courses/favorite', 'StudentCourseController@fetchFavoriteCourses');
    Route::post('/course/favorite/add', 'StudentCourseController@addFavoriteCourse');
    Route::post('/course/favorite/remove', 'StudentCourseController@removeFavoriteCourse');
    Route::post('/course/comments/fetch/{chunk_count}/{page_count}', 'StudentCourseController@fetchComments');
    Route::post('/course/comments/add', 'StudentCourseController@addComment');
    Route::post('/course/comments/remove', 'StudentCourseController@removeComment');
    Route::post('/post/load', 'StudentPostController@loadPost');
    Route::post('/post/score/get', 'StudentPostController@getPostScore');
    Route::post('/post/score/update', 'StudentPostController@updatepPostScore');
    Route::post('/posts/favorite', 'StudentPostController@fetchFavoritePosts');
    Route::post('/post/favorite/add', 'StudentPostController@addFavoritePost');
    Route::post('/post/favorite/remove', 'StudentPostController@removeFavoritePost');
    Route::post('/post/comments/fetch/{chunk_count}/{page_count}', 'StudentPostController@fetchComments');
    Route::post('/post/comments/add', 'StudentPostController@addComment');
    Route::post('/post/comments/remove', 'StudentPostController@removeComment');
    Route::post('/comment/score/get', 'StudentCourseController@getCommentScore');
    Route::post('/comment/score/update', 'StudentCourseController@updateCommentScore');
});

Route::get('/api/test', function(){
    return "Hello test";
});




