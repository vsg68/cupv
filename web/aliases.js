$(function(){

	//Фильтрайия пользователей по домену (alias)
	$('select','#domains_flt').change(function(){

		filter = $('option:selected', '#domains_flt').text();

		$('.hidden').removeClass('hidden');

		if( filter )
			$('td.key:not(:contains("@' + filter+ '"))', '#aliases_box')
					.parent()
					.addClass('hidden');

	});

	$('#fltr').keyup(function(event){

		$('.hidden_filter').removeClass('hidden_filter');

		search_str = $(this).val();

		if( search_str )
			$('td.val:not(:contains("' + search_str + '"))', '#aliases_box')
			.parent()
			.addClass('hidden_filter');

	});

})
