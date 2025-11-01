<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="js/lib/jquery-3.7.1.js" defer></script>
  <script src='js/app.js' defer></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="css/styles.css" />
  <title>My Website</title>
</head>

<body>
  <div class="container">
    <h1>Todo App</h1>
    <div class="container">
      <div class="row">
        <div class="col-6">
          <form id="todo-form">
            <div class="mb-3">
              <label for="id" class="form-label">ID</label>
              <input type="text" class="form-control" id="id" name="id" readonly value="Unknown">
            </div>
            <div class="mb-3">
              <label for="title" class="form-label">Title</label>
              <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <button id="btn-add-todo" type="submit" form="todo-form" class="btn btn-primary">Add Todo</button>
          </form>
        </div>
        <div class="col-6">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Created At</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="todo-table-body">
              <!-- Todo items will be dynamically added here -->
            </tbody>
          </table>
        </div>
      </div>
      <div id="error" class="mt-3 text-danger">
        <!-- Error messages will be displayed here -->
      </div>
    </div>
  </div>
</body>

</html>