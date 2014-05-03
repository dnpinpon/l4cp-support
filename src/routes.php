<?php

Route::model('department', 'TicketDeps');
Route::model('reply', 'TicketAutoreply');
Route::model('spam', 'TicketSpam');
Route::model('status', 'TicketStatuses');
Route::model('escalation', 'TicketEscalations');
Route::model('tickets', 'Ticket');

Route::pattern('department', '[0-9]+');
Route::pattern('reply', '[0-9]+');
Route::pattern('spam', '[0-9]+');
Route::pattern('status', '[0-9]+');
Route::pattern('tickets', '[0-9]+');
Route::pattern('escalation', '[0-9]+');

# json api
Route::group(array('prefix' => 'json/admin', 'before' => 'json|auth.basic|checkuser'), function()
{
	Event::fire('json.admin');
	Support::AdminGroup();
});

# xml api
Route::group(array('prefix' => 'xml/admin', 'before' => 'xml|auth.basic|checkuser'), function()
{
	Event::fire('xml.admin');
	Support::AdminGroup();
});


# web 
Route::group(array('prefix' => 'admin', 'before' => 'auth|checkuser'), function()
{
	Event::fire('support.admin');
	Support::AdminGroup();

});