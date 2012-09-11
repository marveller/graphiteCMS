<?php require 'klein/klein.php';

respond('/klein/posts', function() { echo 'Wszyscy umrzemy'; });

respond(function ($request, $response, $app) {
    // Handle exceptions => flash the message and redirect to the referrer
    $response->onError(function ($response, $err_msg) {
        $response->flash($err_msg);
        //$response->refresh();
    });
});
respond('/klein/[:name]', function ($request) {
	$request->validate(name)->isLen(5, 10);
    echo 'Hello ' . $request->name;	
});

respond(function () {
    echo 'Dzien dobry.';
});

dispatch() ?>