<!-- 
// commons/autoloader.php
// TỰ ĐỘNG LOAD CONTROLLER & MODEL → KHÔNG LỖI DÙ THIẾU FILE

// spl_autoload_register(function ($className) {
//     // Controller
//     if (str_ends_with($className, 'Controller')) {
//         $file = __DIR__ . '/../controllers/' . $className . '.php';
//         if (file_exists($file)) {
//             require_once $file;
//         }
//         return;
//     }

//     // Model
//     if (str_ends_with($className, 'Model')) {
//         $file = __DIR__ . '/../models/' . $className . '.php';
//         if (file_exists($file)) {
//             require_once $file;
//         }
//         return;
//     }
// });