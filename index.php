<?php
require_once __DIR__ . '/Utils/Request.php';

$path = $_SERVER['PATH_INFO'] ?? '/';

if ($path === '/') {
    echo "Linavel V1";
    exit();
}

$split_path = explode('/', ltrim($path, '/'));
$controller = ucfirst($split_path[0]) . 'Controller';
$action = $split_path[1] ?? '';

if (empty($action)) {
    echo 'No action selected';
    exit();
}

$controller_file = __DIR__ . '/Controllers/' . $controller . '.php';

if (file_exists($controller_file)) {
    require_once $controller_file;

    try {
        $controller_object = new $controller();

        if (method_exists($controller_object, $action)) {
            $request = new Request();
            print_r($controller_object->$action($request));
        } else {
            echo "Action '$action' not found in controller '$controller'";
        }

    } catch (\Exception $err) {
        echo $err->getMessage() . "\n";
    }

} else {
    echo 'The controller does not exist';
}