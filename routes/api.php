<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlaintsControllers\PlaintsController;
use App\Http\Controllers\PlaintsControllers\TypePlaintController;
use App\Http\Controllers\PlaintsControllers\SourcePlaintController;

use App\Http\Controllers\PvsControllers\PvsController;
use App\Http\Controllers\PvsControllers\typepvsController;
use App\Http\Controllers\PvsControllers\TypePoliceJudicController;
use App\Http\Controllers\PvsControllers\TypeSourcePvs;

//use App\Http\Controllers\DataPartieController;
use App\Http\Controllers\usersControllers\UserController;
use App\Http\Controllers\usersControllers\RoleController;
use App\Http\Controllers\UsersControllers\UserHasPlaintsController;
use App\Http\Controllers\UsersControllers\UserHasPvsController;

use App\Http\Controllers\AuthControllers\AuthentController;
use App\Http\Controllers\Dossiers_enquete\dossierEnqueteController;
use App\Http\Controllers\PDFController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

  Route::post('/users/login',[AuthentController::class,'login']);

########################--- api pour Plaints ---##########################

Route::namespace('PlaintsControllers')->prefix('/plaint')
       ->middleware(['CheckUser'])->group(function() {

    Route::get('/index',[PlaintsController::class,'index']);
    Route::post('/store', [PlaintsController::class, 'store']);
    Route::put('/update/{id}', [PlaintsController::class, 'update']);
    Route::delete('delete/{id}', [PlaintsController::class, 'destroy']);

    Route::post('/Byreference',[PlaintsController::class,'getplaintByref']);
    Route::post('/Bydate',[PlaintsController::class,'getplaintBydateEnrg']);
    Route::post('/plaints_of_user',[PlaintsController::class, 'getPlaints_of_user']);

    Route::post('addPdf/{idplaint}', [PlaintsController::class, 'PDF_plaint']);
    Route::post('/statistique',[PlaintsController::class,'statistique']);



Route::prefix('/source')->group(function(){
        Route::get('/index',[SourcePlaintController::class,'index'])->middleware(['CheckUser']);
        Route::post('/store', [SourcePlaintController::class, 'store']);
    });
    Route::prefix('/type')->group(function(){
        Route::get('/index',[TypePlaintController::class,'index']);
        Route::post('/store', [TypePlaintController::class, 'store']);
    });
});

           #####################----apis pour PVS---#############################

Route::namespace('PvsControllers')->prefix('/pvs')
      ->middleware(['CheckUser'])->group(function(){
    Route::get('/index',[PvsController::class,'index']);
    Route::post('/store',[PvsController::class, 'store']);
    Route::put('/update/{id}',[PvsController::class, 'update']);
    Route::delete('/delete/{id}',[PvsController::class, 'destroy']);

     Route::post('/ByNumpvs',[PvsController::class, 'cherche_byNumpvs']);
    Route::post('/Bydate',[PvsController::class,'getpvsBydateEnrg']);
    Route::post('/pvs_of_user',[PvsController::class, 'getPvs_of_user']);

    Route::post('addPdf/{idpvs}', [PvsController::class,'PDF_pvs']);// inclut dans pvs
    Route::post('/statistique',[PvsController::class,'statistique']);


    Route::prefix('/type')->group(function(){
        Route::get('/index',[typepvsController::class,'index']);
    });

    Route::prefix('/typesource')->group(function(){
        Route::get('/index',[TypeSourcePvs::class,'index']);
    });

    Route::prefix('/typepolice')->group(function(){
        Route::get('/index',[TypePoliceJudicController::class,'index']);
    });

});

############################-----users=>hasplaints=>haspvs-----###########################

Route::namespace('UsersControllers')->prefix('/users')
       ->middleware(['CheckUser'])->group( function() {
    Route::get('/index',[UserController::class,'index']);
    Route::post('/store', [UserController::class, 'store']);
    Route::post('/update/{id}', [UserController::class, 'update']);// inserer image password
    Route::delete('/delete/{id}', [UserController::class, 'destroy']);
    Route::get('/viceProc',[UserController::class,'index_viceProc']);
    Route::post('/img_sign',[UserController::class,'img_sign']);

    Route::post('/logout',[AuthentController::class,'logout']);
    Route::post('/profile',[AuthentController::class,'profile']);

    Route::prefix('/role')->group(function(){
        Route::get('/index',[RoleController::class,'index']);
        Route::post('/store',[RoleController::class,'store']);
    });

    Route::prefix('/hasplaints')->group(function(){
        Route::post('/index',[UserHasPlaintsController::class,'index']);
        Route::post('/store',[UserHasPlaintsController::class,'store']);
        Route::put('/updateTrait/{id}',[UserHasPlaintsController::class,'updateTrait']);
        Route::delete('/delete/{id}',[UserHasPlaintsController::class,'destroy']);
        Route::get('/mesplaintes',[UserHasPlaintsController::class,'get_mes_plaintes']);
        Route::post('/getArchiveplaint',[UserHasPlaintsController::class,'getArchivePlaint']);

        Route::get('/statistic_vice/{iduser}',[UserHasPlaintsController::class,'statistic_par_vice']);
        Route::post('/change_user/{id_plaint}',[UserHasPlaintsController::class,'change_user']);
        Route::post('/affiche_plainte_statistic',[UserHasPlaintsController::class,'affiche_plainte_statistic']);

        Route::post('/signer_plainte/{id_plainte}',[UserHasPlaintsController::class,'signer_plainte']);
        Route::post('/descision/{id_plainte}',[UserHasPlaintsController::class,'update_descision_plainte']);

    });
############################# userhaspvs ###############"

    Route::prefix('/haspvs')->group(function(){
        Route::post('/index',[UserHasPvsController::class,'index']);
        Route::post('/store',[UserHasPvsController::class,'store']);
        Route::put('/updateTrait/{id}',[UserHasPvsController::class,'updateTrait']);
        Route::delete('/delete/{id}',[UserHasPvsController::class,'destroy']);
        Route::get('/mespvs',[UserHasPvsController::class,'get_mes_pvs']);
        Route::post('/getArchivepvs',[UserHasPvsController::class,'getArchivePvs']);

        Route::post('/change_user/{id_pvs}',[UserHasPvsController::class,'change_user']);
        Route::get('/statistic_vice/{iduser}',[UserHasPvsController::class,'statistic_par_vice']);
        Route::post('/affiche_pvs_statistic',[UserHasPvsController::class,'affiche_plainte_statistic']);

        Route::post('/signer_pvs/{id_pvs}',[UserHasPvsController::class,'signer_pvs']);
        Route::post('/descision/{id_pvs}',[UserHasPvsController::class,'update_descision_pvs']);

        Route::post('/pvs_enquete',[UserHasPvsController::class, 'pvs_enquete_vice']);
    });
});

Route::namespace('Dossiers_enquete')->prefix('/Enquete')
    ->middleware(['CheckUser'])->group( function() {
    Route::get('/index_juge',[dossierEnqueteController::class,'index_juge']);
    Route::get('/index_type',[dossierEnqueteController::class,'index_type']);
    Route::post('/pvs_enquete',[dossierEnqueteController::class,'get_pvs_enquete']);
    Route::get('/all_pvs_enquete',[dossierEnqueteController::class,'all_pvs_enquete']);

    Route::post('/storeDossier',[dossierEnqueteController::class,'storeDossier']);

    ###### cherche un dossier dans un chambre et paginate par chambre #######
    Route::post('/EnqueteChambreCherche/{chambre}',[dossierEnqueteController::class,'EnqueteChambreCherche']);
    Route::get('/paginateChambre1',[dossierEnqueteController::class,'paginateChambre1']);
    Route::get('/paginateChambre2',[dossierEnqueteController::class,'paginateChambre2']);
    ######### tous les dossiers ################
    Route::post('/chercheDossier',[dossierEnqueteController::class,'chercheDossier']);
    Route::get('/paginateTraiter',[dossierEnqueteController::class,'paginateTraiter']);
    ############# dossiersEnquetes d'un juge (chambre)#########
    Route::post('/dossiersJuge',[dossierEnqueteController::class,'dossiersJuge']);
    Route::get('/paginate_mes_fileJuge',[dossierEnqueteController::class,'s']);

    });



