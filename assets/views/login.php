<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="/codebase/webix.css" type="text/css" media="screen" charset="utf-8">
        <link rel="stylesheet" href="/css/main.css" type="text/css" media="screen" charset="utf-8">

        <script src="/codebase/webix.js" type="text/javascript" charset="utf-8"></script>
    </head>
 <body>
        <script type="text/javascript" charset="utf-8">

        webix.ui({
            view:"window",
            width:450,
            headHeight:100,
            position: "center",
            hidden:false,
            head:"<div class='logo'><img src='/gmp.png'></div>",
            body:{
                view:"form",
                elements: [
                    { view:"text", label:'Логин', name:"username" },
                    { view:"text", label:'Пароль', name:"password", type:"password" },
                    {
                        cols:[
                            {},
                            {view:"button", value: "Submit", type:"form", click:function(){

                                var formV= this.getFormView();
                                if (formV.validate()) {

                                    webix.ajax().post("/login/login", formV.getValues(), function(response){

                                        if( ! response )
                                            webix.message({ type:"error", text:"Нет такого пользователя или отсутствуют права на доступ." });
                                        else
                                            window.location.reload(true);
                                    });
                                }
                                else
                                    webix.message({ type:"error", text:"Form data is invalid" });
                            }},
                            {}
                        ]
                    }
                ],
                rules:{
                    $all: webix.rules.isNotEmpty
                },
                elementsConfig:{ labelPosition:"top"}
            }
        });

        </script>

</body>
</html>
