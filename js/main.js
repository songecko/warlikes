var sending = false;
$(document).ready(function()
{
	/**-- Facebook --**/
	$.ajaxSetup({ cache: true });
	$.getScript('//connect.facebook.net/es_LA/all.js', function()
	{
		FB.init({
			appId:  '738588072842792',
	        status: true,
	        xfbml:  true
	    });
	});
	
	
	/**-- Facebook photo selector functions --**/
	var selector, logActivity, callbackAlbumSelected, callbackPhotoUnselected, callbackSubmit;
	var buttonOK = $('#CSPhotoSelector_buttonOK');
	var o = this;
	var addLookUrl;
	
	fbphotoSelect = function(id) 
	{
		// if no user/friend id is sent, default to current user
		if (!id) id = 'me';
		
		callbackAlbumSelected = function(albumId) 
		{
			var album, name;
			album = CSPhotoSelector.getAlbumById(albumId);
			// show album photos
			selector.showPhotoSelector(null, album.id);
		};

		callbackAlbumUnselected = function(albumId) 
		{
			var album, name;
			album = CSPhotoSelector.getAlbumById(albumId);
		};

		callbackPhotoSelected = function(photoId) 
		{
			var photo;
			photo = CSPhotoSelector.getPhotoById(photoId);
			buttonOK.show();
			console.log('Selected ID: ' + photo.id);
		};

		callbackPhotoUnselected = function(photoId) 
		{
			var photo;
			album = CSPhotoSelector.getPhotoById(photoId);
			buttonOK.hide();
		};

		callbackSubmit = function(photoId) {
			var photo;
			photo = CSPhotoSelector.getPhotoById(photoId);
			console.log('Submitted Photo ID: ' + photo.id + 'Photo URL: ' + photo.source);
			
			$.ajax({
                type: "POST",
                url: addLookUrl,
                data: { photoId: photo.id, photoSource: photo.source },
                success: function(data, textStatus, jqXHR)
                {
                	var lookUrl = data.lookUrl;
                	self.location = lookUrl;
                }
			});
		};


		// Initialise the Photo Selector with options that will apply to all instances
		CSPhotoSelector.init({debug: true});

		// Create Photo Selector instances
		selector = CSPhotoSelector.newInstance({
			callbackAlbumSelected	: callbackAlbumSelected,
			callbackAlbumUnselected	: callbackAlbumUnselected,
			callbackPhotoSelected	: callbackPhotoSelected,
			callbackPhotoUnselected	: callbackPhotoUnselected,
			callbackSubmit			: callbackSubmit,
			maxSelection			: 1,
			albumsPerPage			: 6,
			photosPerPage			: 200,
			autoDeselection			: true
		});

		// reset and show album selector
		selector.reset();
		selector.showAlbumSelector(id);
	};
	
	$(".photoSelect").click(function (e) 
	{
		e.preventDefault();
		id = null;
		if ( $(this).attr('data-id') ) id = $(this).attr('data-id');
		addLookUrl = $(this).data('addLookUrl');
		fbphotoSelect(id);
	});
	
	$(".fbShare").click(function (e) 
	{
		e.preventDefault();
		FB.ui({
			method: 'feed',
			link: ''+self.location,
			name: 'Recomendador de Looks de URB',
			caption: 'Acabo de utilizar el Recomendador de Looks de URB. Ingresa para ver cual es el tuyo.',
			description: ' ',
		}, function(response){});
	});
	
	
	/**-- Register Form --**/
	$("form#registerForm").validate(
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
				var errorText = 'Debes resolver los siguienes errores para poder continuar:\n\n';
				for (var i=0; i<errorList.length; i++) 
				{
					//errorText += "- "+errorList[i].message+"<br>";
					errorText += "- "+errorList[i].message+"\n";
				}
				alert(errorText);
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
	
	$('.boton1').mouseenter(function() 
	{
		$(this).addClass('hover');
	}).mouseleave(function() 
	{
		$(this).removeClass('hover');
	});
});