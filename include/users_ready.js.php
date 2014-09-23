
// Связка формы и представления
<?php if( $permissions == $WRITE_LEVEL ): ?>
//
    Users_UserPage.list_bind();
    Aliases_UserPage.list_bind();
    Fwd_UserPage.list_bind();
    Groups_UserPage.list_bind();

    Aliases_AliasPage.list_bind();
    Domains_AliasPage.list_bind();
    Groups_AliasPage.list_bind();


<?php endif; ?>


