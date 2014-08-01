
// Связка формы и представления
<?php //if( $permissions == $WRITE_LEVEL ): ?>
    $$('form_aliases').bind('list_aliases');
    $$('form_groups_RS').bind('list_groups');
    $$('form_groups_Txt').bind('list_groups');
    $$('form_domains').bind('list_domains');

<?php //endif; ?>

    // Фильтрация по текстовым полям
    $$("filter_mbox").attachEvent("onTimedKeyPress", function () {
        //get user input value
        var value = this.getValue().toLowerCase();

        $$('list_aliases').filter(function (obj) {
            return (obj.alias_name.toLowerCase().indexOf(value) >= 0 || obj.delivery_to.toLowerCase().indexOf(value) >= 0);
        })
    });
