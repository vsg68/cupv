
// Связка формы и представления
<?php if( $permissions == $WRITE_LEVEL ): ?>
//
    Users_UserPage.list_bind();
    var CM_Users = new CMenu({listID: "list_users_first"});

    Aliases_UserPage.list_bind();
    var CM_Aliases_User = new CMenu({listID: "list_aliases_first"});

    Fwd_UserPage.list_bind();
    var CM_Fwd_User = new CMenu({listID: "list_fwd_first"});

    Groups_UserPage.list_bind();
    var CM_Groups_User = new CMenu({listID: "list_groups_first"});

    Aliases_AliasPage.list_bind();
    var CM_Aliases_Alias = new CMenu({listID: "list_aliases_second"});

    Domains_AliasPage.list_bind();
    var CM_Domains_Alias = new CMenu({listID: "list_domains_second"});

    Groups_AliasPage.list_bind();
    var CM_Groups_Alias = new CMenu({listID: "list_groups_second"});

	webix.ui(CM_Users.menu);

    webix.ui(CM_Aliases_User.menu);
    webix.ui(CM_Fwd_User.menu);
    webix.ui(CM_Groups_User.menu);
	webix.ui(CM_Aliases_Alias.menu);
    webix.ui(CM_Domains_Alias.menu);
    webix.ui(CM_Groups_Alias.menu);

    
	webix.ui({
		view:"popup",
		id: "nets",
        body: {
			view      :"list",
			url       : "nets/showTable",
			template  :"<div class='fleft arrow'>#net#/#mask#</div> #note#",
			autoheight:true,
			width     :400,
			select    :"multiselect",
			on        : {
				onSelectChange: function(){
					var a = [];
					var sel = this.getSelectedItem(true);
					for(i=0, l=sel.length; i < l; i++){
						a.push(sel[i].net + "/" + sel[i].mask);
					}
					$$("allow_nets").setValue(a.join(","));
				}
        	}
        },
		on:{
			onShow: function(id){
	        		var currentValue = $$("allow_nets").getValue().replace(/\s+/g,"").split(",");
	        		var popupList = this.getChildViews()[0];

	        		popupList.data.each(function(item){
												var currentNet = item.net + "/" + item.mask;
							        			for(var i = 0, l = currentValue.length; i < l; i++)  {
											        if(currentValue[i] == currentNet) {
											        	popupList.select(item.id,true);
											            break;
											        }
											    }
        			});
			}
        },
	}).hide();    

	// Меню генерации пароля
	webix.ui({
		view:"contextmenu",
		width: 250,
        data: ["<span class='webix_icon fa-cog'></span>Сгенерировать пароль"],
        master: $$("pwd")["$view"],
        click: function(id){
        	$$(this.getContext()).setValue(GeneratePassword());
        }
	});

<?php endif; ?>


