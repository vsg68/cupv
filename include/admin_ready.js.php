
// Связка формы и представления
<?php if( $permissions == $WRITE_LEVEL ): ?>
//
    SectPage.list_bind();
    var CM_SectPage = new CMenu({listID: "list_admin_sect"});

    Roles_Page.list_bind();
    var CM_Roles = new CMenu({listID: "list_roles"});

    Rights_Page.list_bind();
    var CM_Rights = new CMenu({listID: "list_roles_rights"});

    Auth_Page.list_bind();
    var CM_Auth = new CMenu({listID: "list_auth"});

    Nets_Page.list_bind();
    var CM_Nets = new CMenu({listID: "list_nets"});
   
    webix.ui(CM_SectPage.menu);
    webix.ui(CM_Roles.menu);
    webix.ui(CM_Rights.menu);
    webix.ui(CM_Auth.menu);
    webix.ui(CM_Nets.menu);

<?php endif; ?>


