<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="/codebase/webix.css" type="text/css" media="screen" charset="utf-8">

        <?= $css_file; ?>

        <script src="/codebase/webix.js" type="text/javascript" charset="utf-8"></script>
        <script src="/js/lib.js" type="text/javascript" charset="utf-8"></script>

        <script type="text/javascript" charset="utf-8">

            var menuitem = {
                view: "menu",
                template: "#name#",
                openAction: "click",
                type: { subsign : true},
                url: "get_menulist/"
            };

            var maintable;   //  в подключенном скрипте ему присваивается значение

            <?php
                if( file_exists($this->pixie->root_dir.'include/'.$ctrl.'.js.php') )
                    include($this->pixie->root_dir.'include/'.$ctrl.'.js.php');
             ?>

            webix.ready(function(){

                webix.ui({
                    container:"mainlayer",
                    id:"mainlayer",
                    type: "space",
                    rows: [{
                            view: "toolbar",
                            css: "tb-color",
                            elements:[ menuitem ]
                            },
                            maintable
                    ]
                });

                 <?php
                    if( file_exists($this->pixie->root_dir.'include/'.$ctrl.'_ready.js.php') )
                        include($this->pixie->root_dir.'include/'.$ctrl.'_ready.js.php');
                 ?>
            });
        </script>
    </head>
    <body>
        <div id="mainlayer"></div>
   </body>
</html>

