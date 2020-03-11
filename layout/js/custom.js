$(function(){

	'use strict';

	// Switch Between Login And SignUp

	$('.login-page h1 span').click(function(){

		$(this).addClass('selected').siblings().removeClass('selected');

		$('.login-page form').hide(100);

		$('.'+ $(this).data('class')).show(100);

	});


	$('.live-name').keyup(function(){

		$('.live-preview .caption h3').text($(this).val());

	});

	$('.live-desc').keyup(function(){

		$('.live-preview .caption p').text($(this).val());

	});

	$('.live-price').keyup(function(){

		$('.live-preview .price-tag').text($(this).val());

	});


/*******************************************************************************/
	// Trigger select box plugin

	$("select").selectBoxIt();


	$('[placeholder]').focus(function(){
		$(this).attr('data-text', $(this).attr('placeholder'));
		$(this).attr('placeholder','');

	}).blur(function(){
		$(this).attr('placeholder',$(this).attr('data-text'));
	});

	// Add * on required fields
	$('input').each(function () {

		if ($(this).attr('required') === 'required') {

			$(this).after('<span class="asterisk">*</span>');
			
		}
	});

	// confirmation message on button

	$('.confirm').click(function(){

		return confirm('Are You Sure?')

	});

});