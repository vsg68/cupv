
// Связка формы и представления
<?php if( $permissions == $WRITE_LEVEL ): ?>
//
    SectPage.list_bind();
    Roles_Page.list_bind();
    Rights_Page.list_bind();
    Auth_Page.list_bind();
   // $$('form_roles').bind('list_roles',function(list_data, form_data){
   //     x = list_data;
//        return list_data.name == form_data.name;
    //})

<?php endif; ?>


