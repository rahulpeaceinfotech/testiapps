<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(relativePath: 'assets/css/style.css') ?>">
</head>
<body>
<div class="col-md-12">
                <div class="card mt-5">
                  
                    <div class="card-header">
                        <h4>Add New Book</h4>
                    </div>
                    <div class="card-body">
                    <form id="book-update-form" method="POST" action="<?= base_url('book/update/' . $bdata['id']) ?>">
                    <input type="hidden" name="_method" value="PUT">
                    <div>
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" value="<?php echo $bdata['title']; ?>">
                        <div class="text-danger title-error"></div>
                    </div>  
                    <div>
                        <label>ISBN</label>
                        <input type="text" name="isbn_no" class="form-control" value="<?php echo $bdata['isbn_no']; ?>">
                        <div class="text-danger isbn-error"></div>
                    </div>
                    <div>
                        <label>Author</label>
                        <input type="text" name="author" class="form-control" value="<?php echo $bdata['author']; ?>">
                        <div class="text-danger author-error"></div>
                    </div>
                    <div>
                        <label>Current Image</label>
                        <img src="<?= base_url('uploads/' . $bdata['book_image']) ?>" alt="Book Image" width="100">
                    </div>  
                    <div>
                        <label>Book Image (optional)</label>
                        <input type="file" name="book_image" class="form-control">
                        <div class="text-danger image-error"></div>
                    </div>
                    <div>
                        <label>Book Categories</label><br>
                        <input type="radio" name="categ" value="fiction" id="categ-fiction" <?php echo ($bdata['categ'] === 'fiction') ? 'checked' : ''; ?>>
                        <label for="categ-fiction">Fiction</label><br>
        
                        <input type="radio" name="categ" value="non-fiction" id="categ-non-fiction" <?php echo ($bdata['categ'] === 'non-fiction') ? 'checked' : ''; ?>>
                        <label for="categ-non-fiction">Non-Fiction</label><br>
        
                        <input type="radio" name="categ" value="mystery" id="categ-mystery" <?php echo ($bdata['categ'] === 'mystery') ? 'checked' : ''; ?>>
                        <label for="categ-mystery">Mystery</label><br>
        
                            <div class="text-danger categ-error"></div>
                    </div>
                    <div>
                        <label>Genres</label><br>
                        <?php
                        $selectedGenres = explode(',', $bdata['genres']); // Convert the stored string back to an array
                        ?>
                        <input type="checkbox" name="genres[]" value="fantasy" id="genre-fantasy" <?= in_array('fantasy', $selectedGenres) ? 'checked' : '' ?>>
                        <label for="genre-fantasy">Fantasy</label><br>
                        
                        <input type="checkbox" name="genres[]" value="sci-fi" id="genre-sci-fi" <?= in_array('sci-fi', $selectedGenres) ? 'checked' : '' ?>>
                        <label for="genre-sci-fi">Sci-Fi</label><br>
                        
                        <input type="checkbox" name="genres[]" value="romance" id="genre-romance" <?= in_array('romance', $selectedGenres) ? 'checked' : '' ?>>
                        <label for="genre-romance">Romance</label><br>
                    </div>
                    <div>
                        <label>State</label>
                        <select name="state_id" id="state-dropdown" class="form-control">
                            <option value="">Select State</option>
                            <?php foreach ($states as $state): ?>
                                <option value="<?= $state['id'] ?>" <?= $state['id'] == $bdata['state_id'] ? 'selected' : '' ?>><?= $state['state_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="text-danger state-error"></div>
                    </div>
                    <div>
                        <label>City</label>
                        <select name="city_id" id="city-dropdown" class="form-control">
                            <option value="">Select City</option>
                            <?php if (!empty($cities)): ?>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?= $city['id'] ?>" <?= $city['id'] == $bdata['city_id'] ? 'selected' : '' ?>><?= $city['city_name'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="text-danger city-error"></div>
                    </div>

                <button type="submit">Add Book</button>
    </form>
                    </div>
                </div>
            </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
$(document).ready(function() {
    $('#book-update-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission
        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'), // Get the action URL from the form
            type: 'POST',
            data: formData, // Serialize form data
            contentType: false, // Important for file upload
            processData: false, // Important for file upload
            dataType: 'json',
            success: function(response) {
                if (response.errors) {
                    // Clear previous error messages
                    $('.text-danger').html('');

                    // Display new error messages
                    if (response.errors.title) {
                        $('.title-error').html(response.errors.title);
                    }
                    if (response.errors.isbn_no) {
                        $('.isbn-error').html(response.errors.isbn_no);
                    }
                    if (response.errors.author) {
                        $('.author-error').html(response.errors.author);
                    }
                    if (response.errors.book_image) {
                        $('.image-error').html(response.errors.book_image);
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
    $('#state-dropdown').change(function() {
        var stateId = $(this).val();
        if (stateId) {
            $.ajax({
                url: '<?= base_url("book/getCities") ?>/' + stateId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#city-dropdown').empty();
                    $('#city-dropdown').append('<option value="">Select City</option>');
                    $.each(data, function(index, city) {
                        $('#city-dropdown').append('<option value="' + city.id + '">' + city.city_name + '</option>');
                    });
                    var currentCityId = '<?= $bdata['city_id'] ?>';
                if (currentCityId) {
                    $('#city-dropdown').val(currentCityId);
                }
                }
            });
        } else {
            $('#city-dropdown').empty();
            $('#city-dropdown').append('<option value="">Select City</option>');
        }
    });

});
</script>

</body>
</html>

