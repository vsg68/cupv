<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="/codebase/webix.css" type="text/css" media="screen" charset="utf-8">
        <link rel="stylesheet" href="/css/main.css" type="text/css" media="screen" charset="utf-8">

        <?= $css_file; ?>

        <script src="/codebase/webix.js" type="text/javascript" charset="utf-8"></script>
        <script src="/js/lib.js" type="text/javascript" charset="utf-8"></script>

        <script type="text/javascript" charset="utf-8">

            function createMenu(){
                var sections = [];
                <?= "sections = ".$pages.";"  ?>
                var items = [];

                for(i=0; i < sections.length; i++){
                    if( sections[i].link == "<?= $ctrl ?>" )  continue;

                    items.push({view:"button",
                                type:"icon",
                                icon:"angle-right",
                                label:sections[i].name,
                                link:sections[i].link,
                                tooltip:sections[i].note,
                                click: function(){  window.location = "/" + this.config.link; },
                                width: 100
                    })
                }
                items.push({}); // заполняю пустые места
                items.push({
                            view:"button",
                            type:"icon",
                            icon:"times-circle",
                            tooltip:"Выход",
                            css:"exit",
                            click: function(){window.location = "/login/logout/"},
                            width: 40
                        });
                return items;
            }

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
                    rows: [
                        {
                        view: "toolbar",
                        css: "tb-color",
                        cols: createMenu()
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

