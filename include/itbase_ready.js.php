
// Связка формы и представления
<?php if( $permissions == $WRITE_LEVEL ): ?>
//
    ITBasePage.list_bind();

    DataPage.list_bind();
   
	webix.ui({
		view:"contextmenu",
		data:["Add_Item","Add_Folder","Edit","Delete",{ $template:"Separator" }, "Copy"],
		master: $$("list_itbase").$view,
		on:{
			"onItemClick": function(id){

				var menuItem   = this.getItem(id).value;
				var selectedId = $$("list_itbase").getSelectedId();
				//  проверка на объект по которому кликнули
				//  если пусто- кликнули мимо
      			// var listId = $$("list_itbase").locate(this.getContext());

				if( ITBasePage.list_Edit[menuItem] != undefined )
					ITBasePage.list_Edit[menuItem]();
			}
		}
	});

	webix.ui({
		view:"contextmenu",
		data:["Add","Edit","Delete"],
		master: $$("list_itemdata").$view,
		on:{
			"onItemClick": function(id){
				var selectedId = $$("list_itemdata").getSelectedId();
				var menuItem   = this.getItem(id).value;

				if( DataPage.list_Edit[menuItem] != undefined )
					DataPage.list_Edit[menuItem]();
			}
		}
	});

	webix.event($$("list_itbase").$view, "contextmenu", function(e){
	  e.preventDefault();
	})



<?php endif; ?>



   $$("list_itbase").filter("#tsect#",2);

