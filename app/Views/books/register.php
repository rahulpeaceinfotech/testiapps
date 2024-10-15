<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css') ?>">
    <style>
        .gradient-custom-3 {
/* fallback for old browsers */
background: #84fab0;

/* Chrome 10-25, Safari 5.1-6 */
background: -webkit-linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5));

/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
background: linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5))
}
.gradient-custom-4 {
/* fallback for old browsers */
background: #84fab0;

/* Chrome 10-25, Safari 5.1-6 */
background: -webkit-linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1));

/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
background: linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1))
}
    </style>
</head>
<body>
    <section class="vh-100 bg-image"
  style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">
  <div class="mask d-flex align-items-center h-100 gradient-custom-3">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
          <div class="card" style="border-radius: 15px;">
            <div class="card-body p-5">
              <h2 class="text-uppercase text-center mb-5">Create an account</h2>

                <form id="user-form" method="POST" action="<?= base_url('user/store') ?>" >

                    <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" id="form3Example1cg" class="form-control form-control-lg uname" name="uname"/>
                    <label class="form-label" for="form3Example1cg">Your Name</label>
                    <div class="text-danger uname-error"></div>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                    <input type="email" id="form3Example3cg" class="form-control form-control-lg uemail" name="uemail" />
                    <label class="form-label" for="form3Example3cg">Your Email</label>
                    <div class="text-danger uemail-error"></div>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                    <input type="password" id="form3Example4cg" class="form-control form-control-lg upass" name="upass" />
                    <label class="form-label" for="form3Example4cg">Password</label>
                    <div class="text-danger upass-error"></div>
                    </div>
                    <div class="d-flex justify-content-center">
                    <button  type="submit" data-mdb-button-init
                        data-mdb-ripple-init class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">Register</button>
                    </div>

                    <p class="text-center text-muted mt-5 mb-0">Have already an account? <a href="#!"
                        class="fw-bold text-body"><u>Login here</u></a></p>

              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script> <!-- Include CKEditor -->
<script>
$(document).ready(function() {

$('#user-form').on('submit', function(e) {
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
                if (response.errors) {
                    // Clear previous error messages
                    $('.text-danger').html('');

                    // Display new error messages
                    if (response.errors.uname) {
                        $('.uname-error').html(response.errors.uname);
                    }
                    if (response.errors.uemail) {
                        $('.uemail-error').html(response.errors.uemail);
                    }
                    if (response.errors.upass) {
                        $('.upass-error').html(response.errors.upass);
                    }
                } else {
                    // Redirect or update the UI on success
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