
// Связка формы и представления
<?php if( $permissions == $WRITE_LEVEL ): ?>
    $$('form_users').bind($$('list_users'));
    $$('form_aliases').bind($$('list_aliases'));
    $$('form_fwd').bind($$('list_fwd'));
    $$('form_groups').bind($$('list_groups'));

<?php endif; ?>

    // Фильтрация по текстовым полям
    $$("filter_mbox").attachEvent("onTimedKeyPress", function () {
        //get user input value
        var value = this.getValue().toLowerCase();

        $$('list_users').filter(function (obj) {
            return (obj.mailbox.toLowerCase().indexOf(value) >= 0 || obj.username.toLowerCase().indexOf(value) >= 0);
        })
    });
