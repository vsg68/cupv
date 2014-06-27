
// Связка формы и представления
<?php if( $permissions == $WRITE_LEVEL ): ?>
    $$('form_user').bind($$('list_user'));
    $$('form_aliases').bind($$('list_aliases'));
    $$('form_fwd').bind($$('list_fwd'));
<?php endif; ?>

    // Фильтрация по текстовым полям
    $$("filter_mbox").attachEvent("onTimedKeyPress", function () {
        //get user input value
        var value = this.getValue().toLowerCase();

        $$('list_user').filter(function (obj) {

            if (obj.mailbox.toLowerCase().indexOf(value) >= 0 || obj.username.toLowerCase().indexOf(value) >= 0)
                return 1;
        })
    });
