function successmessage(heading, messge) {
    $.toast({
        heading: heading,
        text: messge,
        icon: 'success',
        position: 'top-right',
    });
}

function errormessage(heading, messge) {
	$.toast({
		heading: heading,
		text: messge,
		icon: 'error',
		position: 'top-right',

  });
}

$("form[name='edit_user']").validate({

	rules: {
		full_name: "required",
		user_name: "required",
		user_email: "required",
	},
	messages: {
		full_name: "Please enter your first name",
		user_name: "Please enter your last name",
		user_email: "Please enter your email",
	},

	submitHandler: function (form) {

		$.ajax({
	        url: base_url+'admin/User/update_user',
	        data: new FormData($('#edit_user')[0]),
	        type: 'POST',
	        contentType : false,
	        processData : false,
	        success: function ( data ) {
	        	if(data == 1)
	        	{
	        		successmessage('Success', 'User update successfully');
	        	}
	        	else
	        	{
					errormessage('Error', 'User update failed');
	        	}
	        }
	    });


	}
	});


$("form[name='adminprofile']").validate({

	rules: {
		admin_name: "required",
		admin_email: "required",
		admin_password: "required",
	},
	messages: {
		admin_name: "Please enter your name",
		admin_email: "Please enter your email",
		admin_password: "Please enter your password",
	},

	submitHandler: function (form) {

	$.ajax({
        url: base_url+'admin/Login/updateadminprofile',
        data: new FormData($('#adminprofile')[0]),
        type: 'POST',
        contentType : false,
        processData : false,
        success: function ( data ) {
        	if(data == 1)
        	{
        		successmessage('Success', 'Admin profile update successfully');
        	}
        	else
        	{
				errormessage('Error', 'Admin profile update failed');
        	}
        }
    });
}
});

$("form[name='manage_referral_level']").validate({

	rules: {
		level_name: "required",
		 level_percentage: {
	        required: true,
	        number: true
	      }
	},
	messages: {
		level_name: "Please enter level name",
		level_percentage: "Please enter valid level percentage",
	},

	submitHandler: function (form) {

	$.ajax({
        url: base_url+'admin/Refferal/manage_referral_level',
        data: new FormData($('#manage_referral_level')[0]),
        type: 'POST',
        contentType : false,
        processData : false,
        success: function ( data ) {
        	var obj = jQuery.parseJSON(data);
            if (obj.status == 1)
            {
            	successmessage('Success', 'Level update successfully');
            	setTimeout(function () {
						window.location.href = base_url+'admin/referral_level_manage';
					}, 3000);
            }
            else if(obj.status == 2)
            {
            	successmessage('Success', 'Level add successfully');
            	setTimeout(function () {
						window.location.href = base_url+'admin/referral_level_manage';
					}, 3000);
            }
            else
            {
            	errormessage('Error', 'Something went wrong');
            }
        }
    });
}
});

$("form[name='manage_2fa']").validate({

    rules: {
        password: "required",
        two_fa_code: {
            required: true,
            number: true,
            minlength: 6,
            maxlength: 6
          }
    },
    messages: {
        password: "Please enter password",
        two_fa_code: "Please enter valid two-fa-code"
    },

    submitHandler: function (form) {


        $.ajax({
            url: base_url+'admin/Login/update2fa',
            data: new FormData($('#manage_2fa')[0]),
            type: 'POST',
            contentType : false,
            processData : false,
            success: function ( data ) {
                var obj = jQuery.parseJSON(data);

                if(obj.status == true)
                {
                    successmessage('Success', obj.dismessage);
                    setTimeout(function () {
                        window.location.reload();
                    }, 3000);
                }
                else if(obj.status == false)
                {
                    errormessage('Error', obj.dismessage);
                }
            }
        });


    }
    });

$("form[name='send_coin']").validate({

    rules: {
        coin: "required",
        from_address: "required",
        two_address: "required",
        amount: {
            required: true,
            number: true
          }
    },
    messages: {
        coin: "Please select coin type",
        from_address: "Please add from address",
        two_address: "Please enter to address",
        amount: "Please enter valid amount"
    },

    submitHandler: function (form) {

        $.ajax({
            url: base_url+'admin/Core_Wallet/send_coin',
            data: new FormData($('#send_coin')[0]),
            type: 'POST',
            contentType : false,
            processData : false,
            success: function ( data ) {
                console.log(data);
                var obj = jQuery.parseJSON(data);

                if(obj.status == true)
                {
                    successmessage('Success', obj.message);
                    setTimeout(function () {
                        window.location.reload();
                    }, 3000);
                }
                else if(obj.status == false)
                {
                    errormessage('Error', obj.message);
                }
            }
        });


    }
    });

function selectfileadmin()
{
    $("#file").click();
}

function uploadadminprofile()
{
    var name = document.getElementById("file").files[0].name;
    var form_data = new FormData();
    var ext = name.split('.').pop().toLowerCase();
    if (jQuery.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1)
    {
        errormessage('Error', 'Invalid Image File');
    }
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("file").files[0]);
    var f = document.getElementById("file").files[0];
    var fsize = f.size || f.fileSize;
    if (fsize > 2000000)
    {
        errormessage('Error', 'Image File Size is very big');
    }
    else
    {
        form_data.append("file", document.getElementById('file').files[0]);
        $.ajax({
            url: base_url + 'admin/Login/updateadminprofileimg',
            method: "POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            // beforeSend: function () {
            //     $(".loading").show();
            // },
            success: function (data)
            {
            	if(data == 1)
            	{
            		successmessage('Success', 'Admin profile image update successfully');
					setTimeout(function () {
						window.location.reload();
					}, 3000);
            		
            	}
            	else
            	{
            		errormessage('Error', data);
            	}
            }
        });
    }
}

get_tree();
function get_tree()
{
	var user_id = $("#user_id").val();
	$.ajax({
	        type: "POST",
	        data: {user_id: user_id},
	        url: base_url + 'admin/Matrix/getTree',
	        success: function (data) {
	            console.log(data);
	            $('.my_tree').html(data);
	            callagain();
	        }
	    });
}

function callagain()
{
	$('.tree li').hide();
    $('.tree>ul>li').show();
    $('.tree li').on('click', function (e) {
        var children = $(this).find('> ul > li');
        if (children.is(":visible")) children.hide('fast');
        else children.show('fast');
        e.stopPropagation();
    });
}

function findaddress(val)
{
    var coin = val.value;
    if(coin == "btc")
    {
        $("#from_address").val($("#btc_address").val());
    }
    else if(coin == "lqx")
    {
        $("#from_address").val($("#lqx_address").val());
    }
    else
    {
        errormessage('Error', 'Something went wrong');
    }
}