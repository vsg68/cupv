$(function(){

	var tr_aliases = $('tr', '#aliases_box');

	//selected_opts = $(tr_aliases).find('td.key:contains("@t1.gmpro.ru")').parent();

	//Фильтрайия пользователей по домену (alias)
	$('select','#domains_flt').change(function(){

		filter = $('option:selected', '#domains_flt').text();

		if( filter )
			selected_opts = $(tr_aliases).find('td.key:contains("@' + filter+ '")').parent();
		else
			selected_opts = $(tr_aliases);

		$('table','#aliases_box').empty().append(selected_opts);

	});

	$('#fltr').keyup(function(event){

		search_str = $(this).val();
		selected_opts = $(tr_aliases).find('td.val:contains("' + search_str + '")').parent();
		$('table','#aliases_box').empty().append(selected_opts);


	});

})
