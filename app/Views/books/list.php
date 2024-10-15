<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeIgniter List Page</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css') ?>">
    <style>

</style>

</head>
<body>
    <div class="container-fluid bg-danger shadow-sm">
        <div class="text-white m-2 p-2 text-center">
            Simple CodeIgniter 4 CRUD Operation
            <?= session('user')['name']?>

            <button id="logout-button">Logout</button>

        </div>
    </div>

    <div class="container mt-4">
    <?php
                    if(session()->getFlashdata('status'))
                    {
                        echo "<h4>".session()->getFlashdata('status'). "</h4>";
                    }
                    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Books List</h4>
                        <a href="/book/create" class="btn btn-warning float-right">Add New Book</a>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="booksTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>ISBN No</th>
                                    <th>Author</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>

                        </table>
                        <div class="mt-3">
                        <?= $pager->links() ?>


</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">

    <script>
$(document).ready(function() {



    $('#logout-button').on('click', function(e) {
        e.preventDefault(); // Prevent default button action

        $.ajax({
            url: 'user/logout', // Adjust to your logout URL
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                // Handle successful logout
                if (response.message) {
                    alert(response.message);
                    window.location.href = response.redirect; // Redirect to the login page
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText); // Log error for debugging
            }
        });
    });





$('#booksTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('book/fetch') ?>",
                "type": "POST"
            },
            "pageLength": 5,
            "columns": [
                { "data": "id" },
                { "data": "title" },
                { "data": "isbn_no" },
                { "data": "author" },
                {
                    "data": "id", 
                    "render": function(data, type, row) {
                        return '<a href="<?= base_url('book/edit/') ?>' + data + '" class="btn btn-success btn-sm">Edit</a>' +
                               ' <button class="btn btn-danger btn-sm delete-book" data-id="' + data + '">Delete</button>';
                    }
                }
            ]
        });
    
    $(document).on('click', '.delete-book', function() {
            const bookId = $(this).data('id');
            if (confirm('Are you sure you want to delete this book?')) {
                $.ajax({
                    url: '<?= base_url('book/delete') ?>/' + bookId,
                    type: 'get',
                    success: function(response) {
                        alert('Book deleted successfully');
                        $('#booksTable').DataTable().ajax.reload(); // Reload DataTables after deletion
                    },
                    error: function(xhr) {
                        alert('An error occurred while deleting the book.');
                    }
                });
            }
        });
});
</script>

</body>
</html>
