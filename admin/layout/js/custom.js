$(function(){

	'use strict';

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

	// show password eye 

	var pass = $('.password');

	$('.showpass').hover(function() {

		pass.attr('type','text');

	}, function() {

		pass.attr('type','password');

	});

	// confirmation message on button

	$('.confirm').click(function(){

		return confirm('Are You Sure?')

	});


	// Category View Option
	$('.cat h3').click(function(){

		$(this).next('.full-view').fadeToggle(200);

	});

	$('.ordering span').click(function(){

		$(this).addClass('choosed').siblings('span').removeClass('choosed');

		if($(this).data('view') === 'full') {

			$('.full-view').fadeIn(200);

		}else{

			$('.full-view').fadeOut(200);

		}

	});
});