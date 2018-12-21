$(document).ready(function() {
	$('.navbar-toggle').on('click', function() {
		if ($('div#main').hasClass('sidebar-show')) {
			$('div#main').removeClass('sidebar-show');
			$('#sidebar-container').toggle("slide", function() {
				$('#content-container').removeClass('full');
			});
		} else {
			$('div#main').addClass('sidebar-show');
			$('#sidebar-container').toggle("slide", function() {
				$('#content-container').addClass('full');
			});
		}
	});

	$(window).resize(function() {
		$(".placeholder-img > div").height($(".placeholder-img > img").height());
	}).resize();

	$(".toggler > span").click(function(e) {
		$(".toggler > span").toggleClass("list");
		$(".toggler > span").toggleClass("grid");
		$(".art-list").toggleClass("list");
		$(".art-list").toggleClass("grid");
	});
	
	/*$('.upload-picture').on('click',function(e) {
		$('#prd_image').trigger('click');
        alert('hola');
    });*/
	
	$('input,textarea,select','.form-data-one-edit').not(':input[type=button], :input[type=submit], :input[type=reset], :input[type=file]').attr('disabled','disabled');

	$('.one-edit').on("click", function(e) {
		e.preventDefault();
		if ($(this).hasClass('on')) {
			$(this).text("Editar");
			$('.form-control:first',$(this).parent().parent()).attr('disabled','disabled');
		} else {
			$(this).text("Guardar");
			$('.form-control:first',$(this).parent().parent()).removeAttr('disabled');
		}
		$(this).toggleClass('on');
	});
});

