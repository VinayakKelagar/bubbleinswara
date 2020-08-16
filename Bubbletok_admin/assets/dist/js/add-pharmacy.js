$(document).ready(function () {
    //Add Teacher Validation
    $("#addvendor").validate({
        // errorElement:'p',
        rules: {
            'page_id[]': {
                required:true,
            },
            full_name:{
                required:true,
            },
            vendor_name :{
                required:true,
                lettersonly: true
            },
            bulkfile: {
                required: true,
                extension: "xls|csv"
            },
            phar_certi_att: {
                required: true,
                extension: "jpeg|jpg|png|gif"
            },
            phar_vat_att: {
                required: true,
                extension: "jpeg|jpg|png|gif"
            },
            image_name: {
                required: true,
                extension: "jpeg|jpg|png|gif"
            },
            vendor_email: {
                required: true,
                email: true
            },
            vendor_password:{
                required: true,
                minlength:6,
                maxlength:32
            },
            mobile_number:{
                required: true,
                number: true,
                minlength:9,
                maxlength:9
            },
            address:{
                required: true,
            },
            phar_cer_no:{
                required: true,
            },
            phar_vat_no:{
                required: true,
            },

        },


        messages: {
            'page_id[]':{
                required:"Please Select at least one page Permission",
            },
            full_name: "Please Enter Full Name",
            vendor_name: {
                required:"Please Enter Pharmacy Name",
                lettersonly:"Only Alphabets Allowed"
            },
            vendor_email: {
                required: "Please enter email address",
                email: "Please enter a valid email address"
            },
            phar_certi_att:  {
                required:"Please Select File",
                extension:  "Please Select Valid Image File",
            },
            bulkfile:{
                required:"Please Select File",
                extension:  "Please Select Valid CSV or XLS File",
            },
            phar_vat_att:  {
                required:"Please Select File",
                extension:  "Please Select Valid Image File",
            },
            image_name:  {
                required:"Please Select File",
                extension:  "Please Select Valid Image File",
            },
            vendor_password: {
              required:"Please Enter Password",
              minlength:"Password Between 6 to 32 Characters",
              maxlength:"Password Not More Than 32 Characters"
          },

          mobile_number: {
            required: "Please enter Mobile number",
            number: "Please enter valid number",
        },
        address: "Please Enter Address",
        phar_cer_no: "Please Enter Certificate Number",
        phar_vat_no: "Please Enter VAT Number",

    },
    errorElement: "em",
    errorClass:'text-danger',
    // errorPlacement: function (error, element) {
    //         // Add the `help-block` class to the error element
    //         error.addClass("help-block");
    //         // if (element.prop("type") === "checkbox") {
    //         //     error.insertAfter(element.parent("label"));
    //         // } else {
    //         //     error.insertAfter(element);
    //         // }
    //     },
        // highlight: function (element, errorClass, validClass) {
        //     $(element).parents(".form-group").addClass("has-error").removeClass("has-success");
        // },

        // unhighlight: function (element, errorClass, validClass) {
        //     $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
        // },
        submitHandler: function(form) {
            // do other things for a valid form
            $("#addvendor").find('#btnVendor').prop('disabled', true);
           // console.log('hello yes Success');
           $('.form-loader').show();
           setTimeout(function() {
            form.submit();
        }, 1000);
       }

   });

$("#addvendor-edit").validate({
    rules: {
        'page_id[]':{
            required:true,
        },
        full_name:{
            required:true,
        },
        vendor_name :{
            required:true,
            lettersonly: true
        },
        bulkfile: {
            required: false,
            extension: "xls|csv"
        },
        phar_certi_att: {
            required:false,
            extension: "jpeg|jpg|png|gif"
        },
        phar_vat_att: {
            required: false,
            extension: "jpeg|jpg|png|gif"
        },
        image_name: {
            required: false,
            extension: "jpeg|jpg|png|gif"
        },
        vendor_email: {
            required: true,
            email: true
        },
        vendor_password:{
            required: true,
            minlength:6,
            maxlength:32
        },
        mobile_number:{
            required: true,
            number: true,
        },
        address:{
            required: true,
        },
        phar_cer_no:{
            required: true,
        },
        phar_vat_no:{
            required: true,
        },

    },


    messages: {
        'page_id[]':{
            required:"Please Select at least one page Permission",
        },
        full_name: "Please Enter Full Name",
        vendor_name: {
            required:"Please Enter Pharmacy Name",
            lettersonly:"Only Alphabets Allowed"
        },
        vendor_email: {
            required: "Please enter email address",
            email: "Please enter a valid email address"
        },
        phar_certi_att:  {
            required:"Please Select File",
            extension:  "Please Select Valid Image File",
        },
        bulkfile:{
            required:"Please Select File",
            extension:  "Please Select Valid CSV or XLS File",
        },
        phar_vat_att:  {
            required:"Please Select File",
            extension:  "Please Select Valid Image File",
        },
        image_name:  {
            required:"Please Select File",
            extension:  "Please Select Valid Image File",
        },
        vendor_password: {
            required:"Please Enter Password",
            minlength:"Password Between 6 to 32 Characters",
            maxlength:"Password Not More Than 32 Characters"
        },

        mobile_number: {
            required: "Please enter Mobile number",
            number: "Please enter valid number",
        },
        address: "Please Enter Address",
        phar_cer_no: "Please Enter Certificate Number",
        phar_vat_no: "Please Enter VAT Number",

    },
    errorElement: "em",
     errorClass:'text-danger',
    // errorPlacement: function (error, element) {
    //         // Add the `help-block` class to the error element
    //         error.addClass("help-block");
    //         if (element.prop("type") === "checkbox") {
    //             error.insertAfter(element.parent("label"));
    //         } else {
    //             error.insertAfter(element);
    //         }
    //     },
    //     highlight: function (element, errorClass, validClass) {
    //         $(element).parents(".form-group").addClass("has-error").removeClass("has-success");
    //     },

    //     unhighlight: function (element, errorClass, validClass) {
    //         $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
    //     },
        submitHandler: function(form) {
            // do other things for a valid form
          //  $("#addvendor").find('#btnVendor').prop('disabled', true);
            // console.log('hello yes Success');
            $('.form-loader').show();
            setTimeout(function() {
                form.submit();
            }, 1000);
        }

    });


});