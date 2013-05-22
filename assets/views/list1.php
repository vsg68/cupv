<div class='cnt'>
 <?php foreach($users as $user):?>
       <div class='usr'><a href="/users/view/<?php echo $user->user_id;?>"> <?php echo $user->mailbox;?></a> </div>
 <?php endforeach;?>
</div>
<!-- Link to fairy creation form -->

