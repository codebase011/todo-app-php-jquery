<?php
class TodoDAO
{
  private $pdo;

  public function __construct(
    private $host,
    private $user,
    private $password,
    private $database
  ) {
    try {
      $this->pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $password);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      // $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) {
      die("Error en la conexión: " . $e->getMessage());
    }
  }

  public function getTodos()
  {
    try {
      $stmt = $this->pdo->prepare("SELECT * FROM todo");
      $stmt->execute();
      $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // DEBUG: escribir cada fila en el log (no rompe la respuesta HTTP)
      foreach ($resultados as $i => $fila) {
        error_log("Todo row $i: " . json_encode($fila, JSON_UNESCAPED_UNICODE));
      }

      // Devolver los resultados
      return $resultados;
      /*
      // Convertir cada fila de los resultados en un objeto Todo
      $todos = [];
      foreach ($resultados as $fila) {
        $todo = new Todo(
          $fila['title'],
          $fila['description'],
          $fila['created_at']
        );
        // Poner el id ya que no está en el constructor por ser AUTO_INCREMENT
        $todo->setId($fila['id']);

        $todos[] = $todo;
      }

      // Array de objetos Todo
      return $todos;*/
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
      return null;
    }
  }

  public function createTodo(Todo $todo): ?int
  {
    try {
      $stmt = $this->pdo->prepare(
        "INSERT INTO todo (title, description)
             VALUES (:title, :description)"
      );

      $stmt->execute([
        ':title' => $todo->getTitle(),
        ':description' => $todo->getDescription()
      ]);

      return (int)$this->pdo->lastInsertId();
    } catch (PDOException $e) {
      error_log("Error al insertar todo: " . $e->getMessage());
      return null;
    }
  }

  public function updateTodo(Todo $todo)
  {
    try {
      $stmt = $this->pdo->prepare(
        "UPDATE todo 
        SET title = :title, description = :description 
        WHERE id = :id"
      );

      $id = $todo->getId();
      $title = $todo->getTitle();
      $description = $todo->getDescription();

      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->bindParam(':title', $title, PDO::PARAM_STR);
      $stmt->bindParam(':description', $description, PDO::PARAM_STR);

      $stmt->execute();

      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function deleteTodo($id): bool
  {
    try {
      $stmt = $this->pdo->prepare(
        "DELETE FROM todo WHERE id = :id"
      );

      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      $stmt->execute();

      return true;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function __destruct()
  {
    $this->pdo = null;
  }
}
