<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
      <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="row">
        <div class="col-md-3">

        </div>
        <div class="col-md-6 mt-5">
             
                   <form id="user-login" method="POST" action="<?= base_url('user/auth') ?>" >
        <!-- Email input -->
        <div data-mdb-input-init class="form-outline mb-4">
            <input type="email" id="form2Example1" class="form-control" name="email"/>
            <label class="form-label" for="form2Example1">Email address</label>
            <div class="text-danger uemail-error"></div>
        </div>

        <!-- Password input -->
        <div data-mdb-input-init class="form-outline mb-4">
            <input type="password" id="form2Example2" class="form-control" name="password"/>
            <label class="form-label" for="form2Example2">Password</label>
            <div class="text-danger upass-error"></div>
        </div>
        <!-- Submit button -->
        <button  type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Sign in</button>
                            <div class="text-danger error"></div>


  <!-- Register buttons -->
 
</form>
        </div>
        <div class="col-md-3">
            
        </div>
        
    </div>
   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function() {

$('#user-login').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this); // Create a FormData object from the form

        $.ajax({
            url: $(this).attr('action'), // Get the action URL from the form
            type: 'POST',
            data: formData, // Use FormData to send form data including files
            contentType: false, // Important for file upload
            processData: false, // Important for file upload
            dataType: 'json',
            success: function(response) {
                $('.text-danger').html('');

                // Handle validation errors
                if (response.errorspre) {
                    if (response.errorspre.email) {
                        $('.uemail-error').html(response.errorspre.email);
                    }
                    if (response.errorspre.password) {
                        $('.upass-error').html(response.errorspre.password);
                    }
                }
                // Handle generic errors
                if (response.errors) {
                    $('.error').html(response.errors);
                }

                // Handle successful login
                if (response.message) {
                    alert(response.message);
                    window.location.href = response.redirect; // Redirect to the book list
                }
               

                      
            },
            error: function(xhr) {
                console.log(xhr.responseText); // Log error for debugging
            }
        });
    });
    });
    

    </script>
</body>
</html>