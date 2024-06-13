

$router->post('/api/request-reset', function() {
    require 'functions/loginRecover.php';
});


$router->post('/api/reset-password', function() {
    require 'functions/resetPassword.php';
});

$routes->get('/analytics', function() {
    require 'functions/getAnalytics.php';
});

