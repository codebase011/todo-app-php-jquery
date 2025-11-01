<?php
ini_set('display_errors', 1);        // Muestra los errores en pantalla
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);              // Reporta TODOS los tipos de error
// ini_set('display_errors', '0');      // no mostrar errores en pantalla
ini_set('html_errors', 1);         // evita <br><b>Warning</b>...
ini_set('log_errors', 1);          // registra errores en log

require_once 'Todo.php';
require_once 'TodoDAO.php';

$todoDAO = new TodoDAO('localhost', 'root', '', 'todo_app');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    http_response_code(204);
    exit;
}

try {
    switch ($method) {
        case 'GET':
            $todos = $todoDAO->getTodos();
            http_response_code(200);
            echo json_encode($todos, JSON_UNESCAPED_UNICODE);
            break;

        case 'POST':
            // 1) Detecta el tipo de contenido y lee el cuerpo
            // $contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';
            $raw = file_get_contents('php://input');
            error_log("POST RAW BODY: " . $raw); // <-- lo verá

            $input = json_decode(file_get_contents('php://input'), true);
            // Aquí podrías llamar a $todoDAO->createTodo($input)
            // $todoDAO->createTodo($input['title'], $input['description']);
            $nextId = $todoDAO->createTodo(new Todo($input['title'], $input['description']));
            http_response_code(201);
            echo json_encode(["message" => "POST: Resource created.", "nextId" => $nextId]);
            break;

        case 'PUT':
            $raw = file_get_contents('php://input');
            error_log("PUT RAW BODY: " . $raw); // <-- lo verá
            $input = json_decode($raw, true);
            if (isset($input['id'], $input['title'], $input['description'])) {
                $todo = new Todo(
                    $input['title'],
                    $input['description']
                );
                $todo->setId($input['id']);
                if ($todoDAO->updateTodo($todo)) {
                    http_response_code(200);
                    echo json_encode(["message" => "Todo actualizado correctamente: ID " . $input['id']]);
                } else {
                    http_response_code(500);
                    echo json_encode(["message" => "PUT: Error updating resource"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Bad Request: 'id', 'title' and 'description' are required"]);
            }
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? null;
            if (!isset($id)) {
                http_response_code(400);
                echo json_encode(["message" => "Bad Request: 'id' parameter is required"]);
                exit;
            }
            if ($todoDAO->deleteTodo($id)) {
                http_response_code(200);
                echo json_encode(["message" => "DELETE: Resource with ID $id deleted"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "DELETE: Error deleting resource with ID $id"]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
