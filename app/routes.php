<?php
$router->get('/', function() {
    header('Location: ' . SITE_URL . '/tr');
});

$router->get('/tr', 'Index@index');
$router->get('/tr/contact', 'Index@contact');
$router->post('/tr/news/save', 'Index@savenews');
$router->post('/tr/news/update', 'Index@updatenews');
$router->post('/tr/news/delete', 'Index@deletenews');
