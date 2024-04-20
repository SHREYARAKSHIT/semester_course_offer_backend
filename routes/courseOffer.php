<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProcessController;

Route::controller(ProcessController::class)->group(function () {
    Route::post('coursedata', 'coursedata');
    Route::post('getbranchee', 'getbranchee');
    Route::post('gettwoseparatecourse', 'gettwoseparatecourse');
    Route::post('subdelete', 'subdelete');
    Route::post('sub1delete', 'sub1delete');
    Route::get('getsessionyear', 'getsessionyear');
    Route::Post('getbatch', 'getbatch');
    Route::get('getsession', 'getsession');
    Route::get('getcoursee', 'getcoursee');
    Route::Post('getdepartment', 'getdepartment');
    Route::Post('sub1', 'sub1');
    Route::Post('sub2', 'sub1');
    Route::Post('sub10', 'sub10');
    Route::Post('sub7', 'sub1');
    Route::Post('sub8', 'sub8');
    Route::Post('sub9', 'sub8');
    Route::Post('sub11', 'sub1');
    Route::Post('fetchcoursedata','fetchcoursedata');
    Route::get('getdeptoffaculty', 'getdeptoffaculty');
    Route::Post('getsubname', 'getsubname');
    Route::Post('getsubdetail', 'getsubdetail');
});