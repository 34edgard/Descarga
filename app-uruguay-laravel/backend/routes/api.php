<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\{
    UserController,
    AddressController,
    ClassroomController,
    CountryController,
    DisabilityController,
    EducationLevelController,
    EnrollmentController,
    LevelController,
    MedicalConditionController,
    MunicipalityController,
    NationalityController,
    NutritionalStatusController,
    OccupationController,
    ParishController,
    PhoneController,
    ProvenanceController,
    RelationshipController,
    RepresentativeController,
    SchoolYearController,
    SectionController,
    SectorController,
    StateController,
    StudentController,
    TeacherController,
    RoleController
};


// Ruta para CSRF
Route::get('V1/sanctum/csrf-cookie', function () {
    return response()->noContent();
});


Route::prefix('V1/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});


Route::middleware('auth:sanctum')->prefix('V1')->group(function () {
    // Definición de entidades y sus controladores
    $resources = [
        'users'                   => UserController::class,
        'addresses'               => AddressController::class,
        'classrooms'              => ClassroomController::class,
        'countries'               => CountryController::class,
        'disabilities'            => DisabilityController::class,
        'education_levels'        => EducationLevelController::class,
        'enrollments'             => EnrollmentController::class,
        'levels'                  => LevelController::class,
        'medical_conditions'      => MedicalConditionController::class,
        'municipalities'          => MunicipalityController::class,
        'nationalities'           => NationalityController::class,
        'nutritional_statuses'    => NutritionalStatusController::class,
        'occupations'             => OccupationController::class,
        'parishes'                => ParishController::class,
        'phones'                  => PhoneController::class,
        'provenances'             => ProvenanceController::class,
        'relationships'           => RelationshipController::class,
        'representatives'         => RepresentativeController::class,
        'school_years'            => SchoolYearController::class,
        'sections'                => SectionController::class,
        'sectors'                 => SectorController::class,
        'states'                  => StateController::class,
        'students'                => StudentController::class,
        'teachers'                => TeacherController::class,
        'roles'                   => RoleController::class,
    ];

    // Registrar rutas para cada recurso
    foreach ($resources as $resource => $controller) {
        Route::prefix($resource)->group(function () use ($controller, $resource) {
            // Rutas CRUD estándar
            Route::get('/',         [$controller, 'index'])->name("{$resource}.index");
            Route::post('/',        [$controller, 'store'])->name("{$resource}.store");
            Route::get('/{id}',     [$controller, 'show'])->name("{$resource}.show")->where('id', '[0-9]+');
            Route::put('/{id}',     [$controller, 'update'])->name("{$resource}.update")->where('id', '[0-9]+');
            Route::delete('/{id}',  [$controller, 'destroy'])->name("{$resource}.destroy")->where('id', '[0-9]+');

            // Rutas adicionales
            Route::post('/{id}/status', [$controller, 'updateStatus'])->name("{$resource}.update_status");
            Route::get('/paginated',    [$controller, 'paginate'])->name("{$resource}.paginate");
            Route::delete('/delete',    [$controller, 'destroyMultiple'])->name("{$resource}.destroy.multiple");
        });
    }
});
