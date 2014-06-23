<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="/codebase/webix.css" type="text/css" media="screen" charset="utf-8">
        <script src="/codebase/webix.js" type="text/javascript" charset="utf-8"></script>
        <script src="/js/lib.js" type="text/javascript" charset="utf-8"></script>

		<?= $css_file; ?>
		<?= $script_file; ?>

        <script type="text/javascript" charset="utf-8">
            webix.ready(function(){ webix.markup.init(); });
        </script>

    </head>
    <body>
        <div data-view="rows" class="container">
<!--			<div data-view="toolbar"></div>-->
            <div data-view="cols" class="content-left">
                <div data-view="template"  data-select="true" data-width="150"></div>
                <div data-view="cols" class="content">
                        <?php include($subview.'.php'); ?>
                </div>
            </div>
		</div>
    </body>
</html>
