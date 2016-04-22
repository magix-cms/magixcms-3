<?php
require('lib/frontend.inc.php');

$language = new component_core_language('strLangue');
$language->run();

// Create a Router
$router = new router_route();
// Before Router Middleware
$router->before('GET', '/.*', function() use ($router) {
    header('X-Powered-By: Magix CMS');
});
// Root
if(http_request::isGet('strLangue')){
    // Mounting Routes
    $router->mount('/'.$_GET['strLangue'], function() use ($router) {
        $home = new frontend_controller_home($router);
        $home->run();
    });
    $setRouterModule = component_core_router::set();
    if(count($setRouterModule) >= 2){
        $setRouterCollection = component_core_router::setCollection();
        if(array_search($setRouterModule[1],array_flip($setRouterCollection))){
            if(file_exists(component_core_system::basePath().'/plugins/'.$setRouterModule[1])){
                $plugin = $setRouterModule[1];
            }else{
                $plugin = '';
            }
        }
        // Mounting Routes
        switch($setRouterModule[1]){
            case 'pages':
                $router->mount('/'.$_GET['strLangue'].'/'.$setRouterModule[1], function() use ($router) {
                    $pages = new frontend_controller_pages($router);
                    $pages->run();
                });
                break;
            default:
                if($plugin != ''){
                    $router->mount('/'.$_GET['strLangue'].'/'.$setRouterModule[1], function() use ($router,$getModule) {

                    });
                }
                break;
        }
    }
}else{
    $home = new frontend_controller_home($router);
    $home->run();
}
// build router
$router->build();