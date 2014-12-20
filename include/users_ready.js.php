
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


<?php endif; ?>


