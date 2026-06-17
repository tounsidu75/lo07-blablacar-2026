<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

require_once dirname(__DIR__) . '/config/config.php';

spl_autoload_register(static function (string $class): void {
    $paths = [
        dirname(__DIR__) . '/model/' . $class . '.php',
        dirname(__DIR__) . '/controller/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

$action = $_GET['action'] ?? 'home';

try {
    // Point d'entree unique du MVC : l'action de l'URL choisit le controleur.
    switch ($action) {
        case 'home':
            (new HomeController())->home();
            break;

        case 'login':
            (new AuthController())->login();
            break;

        case 'logout':
            (new AuthController())->logout();
            break;

        case 'admin_users':
            (new AdminController())->users();
            break;
        case 'admin_add_driver':
            (new AdminController())->addDriver();
            break;
        case 'admin_add_passenger':
            (new AdminController())->addPassenger();
            break;
        case 'admin_vehicles':
            (new AdminController())->vehicles();
            break;
        case 'admin_add_vehicle':
            (new AdminController())->addVehicle();
            break;
        case 'admin_cities':
            (new AdminController())->cities();
            break;
        case 'admin_add_city':
            (new AdminController())->addCity();
            break;

        case 'driver_vehicles':
            (new DriverController())->vehicles();
            break;
        case 'driver_trips':
            (new DriverController())->trips();
            break;
        case 'driver_add_trip':
            (new DriverController())->addTrip();
            break;
        case 'driver_trip_passengers':
            (new DriverController())->passengers();
            break;
        case 'driver_close_trip':
            (new DriverController())->closeTrip();
            break;

        case 'passenger_reservations':
            (new PassengerController())->reservations();
            break;
        case 'passenger_reserve':
            (new PassengerController())->reserve();
            break;

        case 'exam_superglobals':
            (new ExamController())->superglobals();
            break;
        case 'exam_random_reservations':
            (new ExamController())->randomReservations();
            break;

        case 'innovation_data':
            (new InnovationController())->data();
            break;
        case 'innovation_mvc':
            (new InnovationController())->mvc();
            break;

        default:
            (new HomeController())->notFound();
            break;
    }
} catch (Throwable $exception) {
    http_response_code(500);
    $message = $exception->getMessage();
    require dirname(__DIR__) . '/view/fatal.php';
}
