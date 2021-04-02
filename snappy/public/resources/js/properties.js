

function fetchFromApi(obj) {
    $(obj).prop('disabled', true);
    $(obj).text("fetching");

    $.ajax({
        url:'/fetch-data-api',
        type: 'GET',
        success: function (data) {
            console.log(data);
            $(obj).prop('disabled', false);
            $(obj).text("Fetch From Api");
        }
    });
}

function addProperty() {
    $("#addPropertyModal").modal("show");
}

function editProperty(obj) {
    if($(obj).attr("created_by") == 2) {
        $("#addPropertyModal").modal("show");
    } else {
        alert("you can only edit properties created by admin")
    }
}

function addFormData(obj) {
    // obj.preventDefault();
    var valid = validateForm(true);
    if(valid) {
        var formData = new FormData($('#propertyForm')[0]);

        // formData.append('file', $('input[type=file]')[0].files[0]);

        $.ajax({
            type: "POST",
            url: "add-property",
            data: formData,
            //use contentType, processData for sure.
            contentType: false,
            processData: false,
            success: function(msg) {
                console.log("success");
                $("#addPropertyModal").modal("hide");
                alert("Record created");
            },
            error: function() {
                console.log("failed");
            }
        });
    } else {
        $("#addPropertyError").text("All fields must be filled");
    }
    return false;
}

function editFormData(obj) {
    // obj.preventDefault();
    var valid = validateForm(false);
    if(valid) {
        var formData = new FormData($('#propertyForm')[0]);

        // formData.append('file', $('input[type=file]')[0].files[0]);

        $.ajax({
            type: "POST",
            url: "edit-property",
            data: formData,
            //use contentType, processData for sure.
            contentType: false,
            processData: false,
            success: function(msg) {
                console.log("success");
                $("#addPropertyModal").modal("hide");
                alert("Record updated");
            },
            error: function() {
                console.log("failed");
            }
        });
    } else {
        $("#addPropertyError").text("All fields must be filled");
    }
    return false;
}

function validateForm(file) {
    console.log("validating");
    var parent = $("#propertyForm");
    var valid = true;
    $.each(parent.find("input,textarea"),function() {
        if(!$(this).val().trim().length>0) {
            if(file == true) {
                valid = false;
            }
        }
    });

    $.each(parent.find("select"),function() {
        if(!$(this).val()) {
            valid = false;
        }
    });

    return valid;
}

$(document).ready(function() {
    var dt = $('#properties').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "get-properties",
        "columns": [
            { "data": "property_type" },
            { "data": "type" },
            { "data": "address" },
            { "data": "num_bedrooms" },
            { "data": "num_bathrooms" },
            { "data": "price" },
            {
                "class":          "details-control fa fa-fw fa-wrench",
                "orderable":      false,
                "data":           null,
                "defaultContent": ""
            },
        ]
    } );

    var detailRows = [];

    $('#properties tbody').on( 'click', 'tr td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = $(tr).attr("id");
        console.log(row);
        window.open('/details?id=' + row);
    } );

    // On each draw, loop over the `detailRows` array and show any child rows
    dt.on( 'draw', function () {
        $.each( detailRows, function ( i, id ) {
            $('#'+id+' td.details-control').trigger( 'click' );
        } );
    } );
} );