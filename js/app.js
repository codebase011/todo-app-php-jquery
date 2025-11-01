console.log('App.js is loaded and running');

$(document).ready(function () {
    console.log('Document is ready');
    let editingTodo = false;
    let nextId = null;

    // Initial fetch of todos
    fetchTodos();

    $('#title').focus();

    // Example AJAX GET request to the API
    function fetchTodos() {
        console.log('Fetching todos from API...');
        $.ajax({
            url: 'api.php',
            method: 'GET',
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                console.log('API Response:', response);
                printTodos(response);
            },
            error: function (error) {
                console.error('API Error:', error);
                $('#error').html('Error fetching todos: ' + error.responseText);
            }
        });
    }

    function printTodos(todos) {
        const tableBody = $('#todo-table-body');

        tableBody.empty(); // Clear existing rows
        todos.forEach(function (todo) {
            console.log('Todo:', todo);
            tableBody.append(
                '<tr data-id="' + todo.id + '"><td>' + todo.id + '</td><td>' + todo.title + '</td><td>' + todo.description + '</td><td>' + todo.created_at + '</td><td><button class="btn btn-danger btn-sm delete-todo" data-id="' + todo.id + '">Delete</button></td></tr>'
            );
        });
    }

    $('#todo-table-body').on('click', '.delete-todo', function () {
        const todoId = $(this).data('id');
        console.log('Delete Todo with ID:', todoId);
        // Implement delete functionality here
        // if (confirm('Are you sure you want to delete this todo?')) {
        // AJAX call to delete the todo
        $.ajax({
            url: 'api.php?id=' + todoId,
            method: 'DELETE',
            success: function (response) {
                console.log('Todo deleted:', response);
                fetchTodos(); // Refresh the todo list
                $('#title').focus();
            },
            error: function (error) {
                console.error('Error deleting todo:', error);
            }
        });
        // }
    });

    $('#btn-add-todo').on('click', function (e) {
        e.preventDefault(); // Prevent the default form submission

        const todoId = editingTodo ? $('#id').val() : null;
        const title = $('#title').val();
        const description = $('#description').val();

        if (!title || !description) {
            $('#error').html('Please fill in all fields.');
            return;
        }

        // Check if we are in editing mode
        if (editingTodo) {
            // Implement update functionality here
            console.log('Updating todo with ID:', todoId);
            $.ajax({
                url: 'api.php',
                method: 'PUT',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify({ id: todoId, title, description }),
                success: function (response) {
                    console.log('Todo updated:', response);
                    $('#id').val(nextId);
                    $('#title').val('').focus();           // Clear fields
                    $('#description').val('');
                    $('#error').html('');          // Clear errors
                    $('#btn-add-todo').text('Add Todo');
                    editingTodo = false;
                    fetchTodos(); // Refresh the todo list
                },
                error: function (error) {
                    console.error('Error updating todo:', error);
                    $('#error').html('Error updating todo: ' + error.responseText);
                }
            });
            
            return;
        }

        // If not editing, proceed to create a new todo
        // AJAX call to create a new todo
        $.ajax({
            url: 'api.php',
            method: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({ title, description }),
            // data: { title: title, description: description },
            success: function (response) {
                console.log('Todo created:', response);
                nextId = response.nextId + 1;
                $('#id').val(nextId);
                $('#title').val('').focus();           // Clear fields
                $('#description').val('');
                $('#error').html('');          // Clear errors
                fetchTodos(); // Refresh the todo list
            },
            error: function (error) {
                console.error('Error creating todo:', error);
                $('#error').html('Error creating todo: ' + error.responseText);
            }
        });
    });

    $('#todo-table-body').on('click', 'tr', function (e) {
        if ($(e.target).closest('.delete-todo').length) return; // si viene del botón, salimos

        editingTodo = true;
        const todoId = $(this).data('id');
        console.log('Todo clicked:', todoId);
        // Implement edit functionality here
        $('#id').val(todoId);
        $('#title').focus();
        $('#title').val($(this).find('td:nth-child(2)').text());
        $('#description').val($(this).find('td:nth-child(3)').text());
        $('#btn-add-todo').text('Update Todo');
    });
});