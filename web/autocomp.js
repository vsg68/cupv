$(function(){

	
	var y;
	var options = { serviceUrl:'/users/searchdomain/',
						type:'post',
						//~ onSelect: function(suggestion) {
							//~ // поле ввода
//~ 
							//~ reg = /^[^@]+@/;
							//~ val = $(y).val();
							//~ // сохраняем часть с именем
							//~ var text =
							//~ reg.exec(val)[1];
							//~ //if( text === undefined )
							//~ $(y).val(text + suggestion.value);
							//~ 
						//~ }
					 };
	// Включение автоподстановки
	$(':text.autocomp').live('keydown',function(e){
		
		x = /^[^@]+@/;
	
		if( x.test( e.target.value ) ) {
			if( y === undefined ) {
				//alert('acting');
				$('.autocomp').autocomplete(options);
				y = e.target;
			}
		}
	})
})		
