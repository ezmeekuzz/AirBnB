$(document).ready(function () {
    $('#propertymasterlist').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "/admin/propertymasterlist/getData",
            "type": "POST"
        },
        "columns": [
            // Add columns as needed
            { "data": "propertyname" },
            { "data": "address" },
            { "data": "cleaningfee" },
            { "data": "extraguest" },
            { "data": "hottub" },
            { "data": "petfee" },
            { "data": "basic_number_of_guest" },
            { "data": "guest_limit" },
            {
                "data": null,
                "render": function (data, type, row) {
                    // Customize the last column as needed
                    return '<div class="dropdown">' +
                        '<button type="button" class="btn btn-secondary propertyActionsModal" data-id="' + row.property_id + '" data-slug="' + row.slug + '" data-baseurl="'+ baseUrl  +'">Actions</button>' +
                        '</div>';
                }
            }
        ],
        "columnDefs": [
            // Add class and data-id to the columns as needed
            { "className": "propertyDetails", "targets": [0, 1, 2, 3, 4, 5, 6, 7] }, // Add class to all columns
            { "targets": [0, 1, 2, 3, 4, 5, 6, 7], "createdCell": function (td, cellData, rowData, row, col) {
                // Add data-id to the first column
                $(td).attr('data-id', rowData.property_id);
            }}
        ],
        "createdRow": function (row, data, dataIndex) {
            // Add data-id to the entire row
            $(row).attr('data-id', data.property_id);
        },
        "initComplete": function (settings, json) {
            // Trigger a custom event when DataTable initialization is complete
            $(this).trigger('dt-init-complete');
        }
    });    

    // Wait for DataTable initialization to complete before showing the table
    $('#propertymasterlist').on('dt-init-complete', function () {
        $(this).show();
    });
    $('.delete-btn').on('click', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        const table = $('#propertymasterlist').DataTable();
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: true,
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/admin/propertymasterlist/delete/' + id,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            // Remove the deleted row from the table
                            table.row(row).remove().draw(false);
                        }
                    }
                });
            }
        });
    });
    $(document).on('click', '.propertyDetails', function () {
        // Get the property ID from the data attribute
        var propertyId = $(this).data("id");
        // Make an AJAX request to fetch images based on the property ID
        $.ajax({
            type: "GET",
            url: "/admin/propertymasterlist/propertydetails", // Replace with the actual path to your server-side script
            data: { propertyId: propertyId },
            success: function (response) {
                // Display the images in the modal body
                $("#displayDetails").html(response);
                // Show the modal
                $("#propertyDetails").modal("show");
            },
            error: function () {
                console.error("Error fetching images");
            }
        });
    });
    // Submit form using AJAX
    $('#uploadImages').submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting in the traditional way

        // Check if the file input is empty
        if ($('#image')[0].files.length === 0) {
            // Display error message
            swal({
                type: 'error',
                title: 'Ooopss...',
                text: 'Select an image file(s).',
            });
            return;
        }

        // Cache the property_id value
        var propertyId = $('#property_id').val();

        // Create FormData object
        var formData = new FormData(this);

        // Perform AJAX request
        $.ajax({
            type: 'POST',
            url: '/admin/propertymasterlist/uploadImages', // Replace with your server-side script URL
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Handle success response here
                swal({
                    type: 'success',
                    title: 'Success',
                    text: response.message,
                });
                
                // Clear the form
                $('#uploadImages')[0].reset();

                // Set back the property_id value
                $('#property_id').val(propertyId);
            },
            error: function() {
                alert('Error occurred while inserting data.');
            }
        });
    });
    // Submit form using AJAX
    $('#uploadBanners').submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting in the traditional way

        // Check if the file input is empty
        if ($('#banner')[0].files.length === 0) {
            // Display error message
            swal({
                type: 'error',
                title: 'Ooopss...',
                text: 'Select an image file(s).',
            });
            return;
        }

        // Cache the property_id value
        var propertyId = $('#property_id_').val();

        // Create FormData object
        var formData = new FormData(this);

        // Perform AJAX request
        $.ajax({
            type: 'POST',
            url: '/admin/propertymasterlist/uploadBanners', // Replace with your server-side script URL
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Handle success response here
                swal({
                    type: 'success',
                    title: 'Success',
                    text: response.message,
                });
                
                // Clear the form
                $('#uploadBanners')[0].reset();

                // Set back the property_id value
                $('#property_id_').val(propertyId);
            },
            error: function() {
                alert('Error occurred while inserting data.');
            }
        });
    });
    // Submit form using AJAX
    $('#uploadFA').submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting in the traditional way

        // Check if the file input is empty
        if ($('#icon')[0].files.length === 0) {
            // Display error message
            swal({
                type: 'error',
                title: 'Ooops...',
                text: 'Select an image file(s).',
            });
            return;
        }

        // Check if the feature input is empty
        if ($('#feature').val().trim() === '') {
            // Display error message
            $('#feature-error').text('Feature/Amenities field is required.');
            return;
        } else {
            $('#feature-error').text('');
        }

        // Cache the property_id value
        var propertyId = $('#property_id__').val();

        // Create FormData object
        var formData = new FormData(this);

        // Perform AJAX request
        $.ajax({
            type: 'POST',
            url: '/admin/propertymasterlist/uploadFA', // Replace with your server-side script URL
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Handle success response here
                swal({
                    type: 'success',
                    title: 'Success',
                    text: response.message,
                });

                // Clear the form
                $('#uploadFA')[0].reset();

                // Set back the property_id value
                $('#property_id__').val(propertyId);
            },
            error: function() {
                alert('Error occurred while inserting data.');
            }
        });
    });
    $(document).on('click', '.propertyActionsModal', function () {
        var modalContents = $("#modalContents");
        var buttonMenus = $("#buttonMenus");
        var uploadImageDiv = $("#uploadImageDiv");
        var uploadBannerDiv = $("#uploadBannerDiv");
        var uploadFADiv = $("#uploadFADiv");
    
        // Slide and toggle visibility for buttonMenus
        buttonMenus.show().animate({
            marginLeft: '0',
            opacity: 1
        }, 100);
    
        // Slide and toggle visibility for uploadImageDiv
        animateInsideModalContents(uploadImageDiv);
    
        // Slide and toggle visibility for uploadBannerDiv
        animateInsideModalContents(uploadBannerDiv);
    
        // Slide and toggle visibility for uploadBannerDiv
        animateInsideModalContents(uploadFADiv);
    
        var propertyId = $(this).data('id');
        var slug = $(this).data('slug');
        var baseurl = $(this).data('baseurl');
        // Use propertyId to fetch corresponding data or links
    
        // Update modal content based on the data
        $('#viewPageBtn').attr('href', baseurl + slug);
        $('#pricingBtn').attr('href', baseurl + 'admin/pricing/' + propertyId);
        $('#editPropertyBtn').attr('href', baseurl + 'admin/edit-property/' + propertyId);
        $('.UploadImage').attr('data-id', propertyId);
        $('.UploadBanner').attr('data-id', propertyId);
        $('.UploadFA').attr('data-id', propertyId);
        $('.delete-btn').attr('data-id', propertyId);
    
        // Show the modal
        $('#propertyActionsModal').modal('show');
    });
    function animateInsideModalContents(element) {
        element.animate({
            marginLeft: '0',
            opacity: 1
        }, {
            duration: 50,
            start: function () {
                element.show();
            },
            complete: function () {
                element.css({
                    marginLeft: '-100%',
                    opacity: 0
                }).hide();
            }
        });
    }
    $(document).on('click', '.UploadImage', function () {
        var buttonMenus = $("#buttonMenus");
        var uploadImageDiv = $("#uploadImageDiv");
        var propertyId = $(this).data('id');
        $('#property_id').val(propertyId);
        // Slide and toggle visibility for buttonMenus
        buttonMenus.animate({
            marginLeft: '-100%',
            opacity: 0
        }, 100, function () {
            buttonMenus.hide();
        });

        // Slide and toggle visibility for uploadImageDiv
        uploadImageDiv.show().animate({
            marginLeft: '0',
            opacity: 1
        }, 50);
    });
    $(document).on('click', '.UploadBanner', function () {
        var buttonMenus = $("#buttonMenus");
        var uploadBannerDiv = $("#uploadBannerDiv");
        var propertyId = $(this).data('id');
        $('#property_id_').val(propertyId);
        // Slide and toggle visibility for buttonMenus
        buttonMenus.animate({
            marginLeft: '-100%',
            opacity: 0
        }, 100, function () {
            buttonMenus.hide();
        });

        // Slide and toggle visibility for uploadBannerDiv
        uploadBannerDiv.show().animate({
            marginLeft: '0',
            opacity: 1
        }, 50);
    });
    $(document).on('click', '.UploadFA', function () {
        var buttonMenus = $("#buttonMenus");
        var uploadFADiv = $("#uploadFADiv");
        var propertyId = $(this).data('id');
        $('#property_id__').val(propertyId);
        // Slide and toggle visibility for buttonMenus
        buttonMenus.animate({
            marginLeft: '-100%',
            opacity: 0
        }, 100, function () {
            buttonMenus.hide();
        });

        // Slide and toggle visibility for uploadFADiv
        uploadFADiv.show().animate({
            marginLeft: '0',
            opacity: 1
        }, 50);
    });
    $(document).on('click', '.returnBtn', function () {
        var buttonMenus = $("#buttonMenus");
        var uploadImageDiv = $("#uploadImageDiv");
        var uploadBannerDiv = $("#uploadBannerDiv");
        var uploadFADiv = $("#uploadFADiv");

        // Slide and toggle visibility for buttonMenus
        buttonMenus.show().animate({
            marginLeft: '0',
            opacity: 1
        }, 100);

        // Slide and toggle visibility for uploadImageDiv
        uploadImageDiv.animate({
            marginLeft: '-100%',
            opacity: 0
        }, 50, function () {
            uploadImageDiv.hide();
        });
        // Slide and toggle visibility for uploadBannerDiv
        uploadBannerDiv.animate({
            marginLeft: '-100%',
            opacity: 0
        }, 50, function () {
            uploadBannerDiv.hide();
        });
        // Slide and toggle visibility for uploadFADiv
        uploadFADiv.animate({
            marginLeft: '-100%',
            opacity: 0
        }, 50, function () {
            uploadFADiv.hide();
        });
    });
});