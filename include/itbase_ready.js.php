
// Связка формы и представления
<?php if( $permissions == $WRITE_LEVEL ): ?>
//
    ITBasePage.list_bind();
	// Контекстное меню
	var CM_ITBase = new CMenu({listID: "list_itbase", separator_index: 4});
    
    DataPage.list_bind();
    // Контекстное меню
	var CM_Data = new CMenu({listID: "list_itemdata"});


	
	// Инициализация контекстного меню
	webix.ui(CM_ITBase.menu);
	webix.ui(CM_Data.menu);
	
	webix.event($$("list_itbase").$view, "contextmenu", function(e){
	  e.preventDefault();
	})



<?php endif; ?>



   $$("list_itbase").filter("#tsect#",2);

