
// Связка формы и представления
<?php if( $permissions == $WRITE_LEVEL ): ?>
//
    ITBasePage.list_bind();

    DataPage.list_bind();
   
<?php endif; ?>



   $$("list_itbase").filter("#tsect#",2);

