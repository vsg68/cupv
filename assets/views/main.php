<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="/codebase/webix.css" type="text/css" media="screen" charset="utf-8">
        <script src="/codebase/webix.js" type="text/javascript" charset="utf-8"></script>
        <script src="/js/lib.js" type="text/javascript" charset="utf-8"></script>


			<?= $css_file; ?>
			<?= $script_file; ?>
    </head>
    <body>
        <div class="container">
			<table class='container-inner'>
				<tr>
					<td style='padding-left: 10px;' colspan=2>
						<div class="content-top ui-corner-all">
							<div class='ui-widget ui-corner-all pagemenu logout'>
								<a href='/login/logout'><div id='_exit' class='pagetabmenu ' title='Logout' style='background: url(/images/exit.png)'></div></a>
							</div>
							<?php
								if(isset($pages) ) {
									foreach($pages as $page):
							?>

							<div class='ui-widget ui-corner-all pagemenu'>
								<a href='/<?= $page->link ?>'>
									<div id='menu_<?= strtolower($page->link) ?>'class='pagetabmenu <?= ($menuitems[0]->section_id == $page->id) ? 'pagetabmenu-selected ' : '' ?>' title='<?= $page->name ?>'
									<?php
										$filename = $_SERVER['DOCUMENT_ROOT'].'/images/'.strtolower($page->name).'_small.png';
										//echo $filename;
										if( file_exists($filename))
										 echo " style='background: url(/images/".basename($filename).")'";
									?>>
									</div>
								</a>
							</div>

							<?php
									endforeach;
								}
							?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="content-left">
					<?php foreach( $menuitems as $item ): ?>
						<div class='theme box-shadow ui-corner-all' title='<?= $item->name ?>'>
							<a href="/<?= $item->class ?>/"><div  id='<?= $item->class ?>'  class='pagetab'></div></a>
						</div>
					<?php endforeach; ?>
					</td>
					<td class="content">
						<?php include($subview.'.php'); ?>

                    </td>
				</tr>
			</table>
		</div>
    </body>
</html>
