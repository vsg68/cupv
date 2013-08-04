
	$('.accordion').accordion({canToggle: true});

	$('h3 a').click(function(){

			var ind = $('h3 a').index(this);
			$('.ptr')
					 .filter( function(){ return $('.ptr').index(this) != ind } )
					 .removeClass('ptr-hover');

			$(this).children('.ptr').toggleClass('ptr-hover');

		})

