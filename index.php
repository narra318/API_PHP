<?php
header('Content-Type: application/json');

// Ruta base
$base_path = 'https://649071941e6aa71680cb43d5.mockapi.io/api-calificaciones';

// Rutas y acciones
$routes = [
    '/calificaciones' => [
        'GET' => 'getCalificaciones',
        'POST' => 'createCalificacion',
    ],
    '/calificaciones/{id}' => [
        'GET' => 'getCalificacion',
        'PUT' => 'updateCalificacion',
        'DELETE' => 'deleteCalificacion',
    ],
];

// Verificar la ruta y el verbo HTTP
$path = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Buscar la acción correspondiente a la ruta y el verbo HTTP
$action = null;
foreach ($routes as $route => $verbs) {
    $pattern = str_replace('/', '\/', $route);
    $pattern = preg_replace('/\{(\w+)\}/', '(\w+)', $pattern);
    if (preg_match("/^$pattern$/", $path, $matches) && isset($verbs[$method])) {
        $action = $verbs[$method];
        break;
    }
}

// Ejecutar la acción correspondiente
if ($action !== null) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = isset($matches[1]) ? $matches[1] : null;
    $response = call_user_func($action, $id, $data);
    echo json_encode($response);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Ruta no encontrada']);
}

// Acción para obtener todas las calificaciones
function getCalificaciones()
{
    global $base_path;
    $url = $base_path . '/calificaciones';
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Acción para obtener una calificación por ID
function getCalificacion($id)
{
    global $base_path;
    $url = $base_path . '/calificaciones/' . $id;
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Acción para crear una calificación
function createCalificacion($data)
{
    global $base_path;
    $url = $base_path . '/calificaciones';
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode($data),
        ],
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    return json_decode($response, true);
}

// Acción para actualizar una calificación por ID
function updateCalificacion($id, $data)
{
    global $base_path;
    $url = $base_path . '/calificaciones/' . $id;
    $options = [
        'http' => [
            'method' => 'PUT',
            'header' => 'Content-Type: application/json',
            'content' => json_encode($data),
        ],
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    return json_decode($response, true);
}

// Acción para eliminar una calificación por ID
function deleteCalificacion($id)
{
    global $base_path;
    $url = $base_path . '/calificaciones/' . $id;
    $options = [
        'http' => [
            'method' => 'DELETE',
        ],
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    return json_decode($response, true);
}
?>
