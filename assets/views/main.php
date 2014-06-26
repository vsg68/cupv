<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="/codebase/webix.css" type="text/css" media="screen" charset="utf-8">

        <?= $css_file; ?>
        <!--        <link rel="stylesheet" href="/css/style.css" type="text/css" media="screen" charset="utf-8">-->
<!--        <link rel="stylesheet" href="/css/smoothness/images.css" type="text/css" media="screen" charset="utf-8">-->
        <script src="/codebase/webix.js" type="text/javascript" charset="utf-8"></script>
        <script src="/js/lib.js" type="text/javascript" charset="utf-8"></script>

        <script type="text/javascript" charset="utf-8">

        var menuitem = {
            view: "menu",
            template: function(obj) {
                if(obj.name == "Логи")
                    return "<i>"+obj.name+"</i>";
                return obj.name;
            },
            type: { subsign : true},
            url: "get_menulist/"
        }

        var maintable;   //  в подключенном скрипте ему присваивается значение

        <?php include($script_file); ?>

        webix.ready(function(){


            webix.ui({
                container:"mainlayer",
                id:"mainlayer",
                type: "space",
                rows: [{
                        view: "toolbar",
                        elements:[ menuitem ]
                        },
                        maintable
                ]
            });

//@TODO
// 1)разделить пользовательский скрипт на тот, что выполняется по готовности и описание и подключить
// 2) растяжение на всю страницу главной таблицы

            $$('form_user').bind($$('list_user'));
            $$('form_aliases').bind($$('list_aliases'));
            $$('form_fwd').bind($$('list_fwd'));

            // Фильтрация по текстовым полям
            $$("filter_mbox").attachEvent("onTimedKeyPress", function () {
                //get user input value
                var value = this.getValue().toLowerCase();

                $$('list_user').filter(function (obj) {

                    if (obj.mailbox.toLowerCase().indexOf(value) >= 0 || obj.username.toLowerCase().indexOf(value) >= 0)
                        return 1;
                })
            });


        });
        </script>


    </head>
    <body>
        <div id="mainlayer"></div>
<!--        <div data-view="rows" data-type="space">-->
<!--        	<div data-view="toolbar">-->
<!--                <div id="menuitem"></div>-->
<!--            </div>-->
<!--            <div data-view="rows">-->
<!--                    --><?php //include($subview.'.js'); ?>
<!--            </div>-->
<!--		</div>-->
   </body>
</html>

