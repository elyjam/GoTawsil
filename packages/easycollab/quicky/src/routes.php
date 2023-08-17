<?php
//Route::resource('/quicky', 'EasyCollab\Quicky\QuickyController');
Route::any('/quicky', 'EasyCollab\Quicky\QuickyController@index')->name('quicky');