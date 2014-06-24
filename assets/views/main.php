<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="/codebase/webix.css" type="text/css" media="screen" charset="utf-8">
        <link rel="stylesheet" href="/css/style.css" type="text/css" media="screen" charset="utf-8">
        <link rel="stylesheet" href="/css/smoothness/images.css" type="text/css" media="screen" charset="utf-8">
        <script src="/codebase/webix.js" type="text/javascript" charset="utf-8"></script>
        <script src="/js/lib.js" type="text/javascript" charset="utf-8"></script>

		<?= $css_file; ?>
		<?= $script_file; ?>

        <script type="text/javascript" charset="utf-8">
            webix.ready(function(){ webix.markup.init(); });
        </script>

    </head>
    <body>
        <div data-view="rows">
<!--			<div data-view="toolbar"></div>-->
            <div data-view="cols" data-type="clean" >
                <div data-view="list" data-width="150" data-css="menulist">
                    <ul data-view="data">
                        <?php  foreach( $menuitems as $item ): ?>
                        <li class="menuitem" >
                            <div class='theme box-shadow ui-corner-all' title='<?= $item->name ?>'>
                                <a href="/<?= $item->class ?>/"><div  id='<?= $item->class ?>'  class='pagetab'></div></a>
                            </div>
                        </li>
                        <?php  endforeach; ?>
                    </ul>
                </div>
                <div data-view="cols">
                        <?php include($subview.'.php'); ?>
                </div>
            </div>
		</div>
   </body>
</html>
