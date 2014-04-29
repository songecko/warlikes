function showAlertModal(title, message)
{
	$('#alertModal .title').html(title);
	$('#alertModal').show();
}

function hideModal()
{
	$('.modal').fadeOut('fast');
}

$(document).ready(function()
{
	/**-- Facebook --**/
	$.ajaxSetup({ cache: true });
	$.getScript('//connect.facebook.net/es_LA/all.js', function()
	{
		FB.init({
			appId:  '551213981665418',
	        status: true,
	        xfbml:  true
	    });
		
		FB.Canvas.setSize({ width: 810, height: 850 });
	});
		
	$(".fbShare").click(function (e) 
	{
		e.preventDefault();
		FB.ui({
			method: 'feed',
			link: ''+self.location,
			name: 'Guerra de Likes de Easy',
			caption: $(this).data('title'),
			description: ' ',
		}, function(response){});
	});
	
	
	/**-- Register Form --**/
	$("form#register-form").validate(
	{
		onkeyup: false,
		onclick: false,
		onfocusout: false,
		errorPlacement: function(error, element) 
		{
		},
		highlight: function(element, errorClass, validClass) 
		{
		    $(element).addClass(errorClass).removeClass(validClass);
		},
		unhighlight: function(element, errorClass, validClass) 
		{
		    $(element).removeClass(errorClass).addClass(validClass);
		},
		invalidHandler: function(event, validator)
		{
			//alert("Debes completar todos los campos correctamente para continuar.");
		},
		showErrors: function(errorMap, errorList) 
		{
			if(errorList.length > 0)
			{
				var errorText = 'Debes resolver los siguienes errores para poder continuar:<br><br>';
				for (var i=0; i<errorList.length; i++) 
				{
					//errorText += "- "+errorList[i].message+"<br>";
					errorText += "- "+errorList[i].message+"<br>";
				}
				showAlertModal("¡Debes completar todos los datos!");
				//$('#errorsModal .modal-body').html(errorText);
				//$('#errorsModal').modal('show');
			}
			this.defaultShowErrors();
		},/*
		submitHandler: function(form)
		{
		}*/
	});
	
	
	/**-- Misc --**/
	$('.modal-close').click(function(e)
	{
		e.preventDefault();
		hideModal();
	});
});