<?php
namespace App;

class Router {
    public function run(string $path) {
        if ($path !== '/' && str_ends_with($path, '/')) $path = rtrim($path, '/');
        switch ($path) {
            case '/':
                $title='Incidencias RD'; $view=__DIR__.'/../views/home.php';
                include __DIR__.'/../views/layout.php'; break;

            case '/incidents':
                require __DIR__.'/Controllers/IncidentController.php';
                (new \App\Controllers\IncidentController())->index(); break;

            case '/incidents/new':
                require __DIR__.'/Controllers/IncidentController.php';
                (new \App\Controllers\IncidentController())->createPage(); break;

            case '/incidents/store':
                require __DIR__.'/Controllers/IncidentController.php';
                (new \App\Controllers\IncidentController())->store(); break;

            case '/comments/store':
                require __DIR__.'/Controllers/CommentController.php';
                (new \App\Controllers\CommentController())->store(); break;

            case '/corrections/store':
                require __DIR__.'/Controllers/CorrectionController.php';
                (new \App\Controllers\CorrectionController())->store(); break;

            case '/api/incidents':
                require __DIR__.'/Controllers/IncidentController.php';
                (new \App\Controllers\IncidentController())->apiRecent(); break;

            case '/super':
                require __DIR__.'/Controllers/AdminController.php';
                (new \App\Controllers\AdminController())->panel(); break;

            case '/super/validate':
                require __DIR__.'/Controllers/AdminController.php';
                (new \App\Controllers\AdminController())->validateList(); break;

            case '/super/approve':
                require __DIR__.'/Controllers/AdminController.php';
                (new \App\Controllers\AdminController())->approve(); break;

            case '/super/reject':
                require __DIR__.'/Controllers/AdminController.php';
                (new \App\Controllers\AdminController())->reject(); break;

            case '/super/merge':
                require __DIR__.'/Controllers/AdminController.php';
                (new \App\Controllers\AdminController())->merge(); break;

            default:
                http_response_code(404); echo "Página no encontrada";
        }
    }
}

