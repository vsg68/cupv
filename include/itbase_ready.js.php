
// Связка формы и представления
<?php if( $permissions == $WRITE_LEVEL ): ?>
//
    ITBasePage.list_bind();

    DataPage.list_bind();
   
	webix.ui({
		view:"contextmenu",
		data:["Add_Item","Add_Folder","Delete",{ $template:"Separator" }, "Copy"],
		master: $$("list_itbase"),
		on:{
			"onItemClick": function(id){
				var selectedId = this.getContext().obj.getSelectedId();
				var menuItem   = this.getItem(id).value;
				
				if( ITBasePage.list_Edit[menuItem] != undefined )
					ITBasePage.list_Edit[menuItem](selectedId);
			}
		}
	});







<?php endif; ?>



   $$("list_itbase").filter("#tsect#",2);

