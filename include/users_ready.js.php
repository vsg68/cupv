
// Связка формы и представления
<?php if( $permissions == $WRITE_LEVEL ): ?>
//
    Users_UserPage.list_bind();
    Aliases_UserPage.list_bind();
    Fwd_UserPage.list_bind();
    Group_UserPage.list_bind();

    Aliases_AliasPage.list_bind();
    Domains_AliasPage.list_bind();
    Groups_AliasPage.list_bind();


<?php endif; ?>

    // Фильтрация по текстовым полям
//    $$("filter_users").attachEvent("onTimedKeyPress", function () {
//        //get user input value
//        var value = this.getValue().toLowerCase();
//
//        $$('list_users').filter(function (obj) {
//            return (obj.mailbox.toLowerCase().indexOf(value) >= 0 || obj.username.toLowerCase().indexOf(value) >= 0);
//        })
//    });

