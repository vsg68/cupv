<!DOCTYPE html>
<html>
<head>
    <META http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/codebase/webix.css" type="text/css" media="screen" charset="utf-8">
    <link rel="stylesheet" href="/css/main.css" type="text/css" media="screen" charset="utf-8">
    <script src="/codebase/webix_debug.js" type="text/javascript" charset="utf-8"></script>
    <title>Something</title>
</head>
<style>
    /*.folder_grp*/
   /*.groups .webix_tree_folder_open,*/
   /*.groups .webix_tree_folder*/
    /*{*/
        /*background-image: url(images/groups.png);*/
        /*background-position: 0px 4px;*/
    /*}*/
   /*.groups .webix_tree_file{*/
        /*background-image: url(images/usr1.png);*/
        /*background-position: 0px 4px;*/
    /*}*/
    /*.my_style{*/
        /*background-color:#FFAAAA;*/
    /*}*/
</style>

<body>

<script type="text/javascript" charset="utf-8">
        //plain json data, based on objects

var tb = {
    view: "toolbar",
    height: 30,
    cols: [
        {
            view: 'text',
            css:"filter",
            placeholder: 'Filter..',
            width:200
//            on: {
//                "onTimedKeyPress": function() {
//                    var value = this.getValue().toLowerCase();
//                    $$('list_' + self.objID).filter( function(obj) {
//                        return self.filterRule(obj, value);
//                    } );
//                }
//            }
        },
                {view: "label", label: "Фильтр поиска"},
    ]
};
var mylist = {}
var treea = {
    id: "form_tree",
    view: "form",
    elementsConfig: {labelWidth: 130},
    elements: [
        {view:'radio',id:'l_1', label:'MAIL',options:[{id:'s_1', value:'NONE'},{id:'s_2', value:'READ'},{id:'s_3', value:'WRITE'}]},
        {view:'radio',id:'l_3', label:'SQUID',options:[{id:'s_1', value:'NONE'},{id:'s_2', value:'READ'},{id:'s_3', value:'WRITE'}]},
        {view:'radio',id:'l_4', label:'ADMIN',options:[{id:'s_1', value:'NONE'},{id:'s_2', value:'READ'},{id:'s_3', value:'WRITE'}]},
        {view:'radio',id:'l_5', label:'RADIUS',options:[{id:'s_1', value:'NONE'},{id:'s_2', value:'READ'},{id:'s_3', value:'WRITE'}]},
        {},
        { view: "button", value: "Cancel", width: 70, click: function(){ this.getFormView().config.cancel()} },
        { view: "button", value: "Save", width: 70, type: "form", click: function(){ this.getFormView().config.save_form()} },
        {}
    ]}

function find(){
    var self = this;
    self.define({disabled:true});

    $$("list_log").clearAll();
    webix.ajax().get("/logs/show/", this.getFormView().getValues(), function (data){
        if (data)
            $$("list_log" ).parse(data);
        else
            webix.message("Данных нет");
        self.define({disabled:false});
    })
}

var startDate = 0;
var intervalID;

function fnTail() {

    $$("list_log").clearAll();

    if(this.config.icon == "play") {
        this.define({icon:"stop", label: "Стоп"});
        intervalID = setInterval(function(){
            webix.ajax().get('/logs/tail/',{'startDate': startDate}, function(response) {
                len = response.length;
                if(len) {
                    startDate = response[(len-1)].ReceivedAt;
                    $$("list_log" ).parse(response);
                    $$("list_log" ).scrollTo(0,9999);
                }
            });
        }, 3000);
    }
    else {
        this.define({icon:"play", label: "Старт"});
        clearInterval(intervalID);
    }
}

var nowMsgId, changeClass, prevMsgId;
var mf = {
     rows: [
         { view:"toolbar",height: 30, cols:[
             {view:"toggle", type:"iconButton", icon:"play", label:"Старт", width: 90, click: "fnTail()"}
         ]}, //1st row
         {
             id: "list_",
             css: "roles",
             view: "tree",
             select: true,
             template: function(obj, com){
                 // Подставляем свою иконку для группы
                 var icon = obj.$parent ? "<img src='/" + obj.image +".png' style='float:left; margin:3px 4px 0px 1px;'>" : "<div class='webix_tree_folder'></div>";
                 return com.icon(obj, com) + icon + '<span>'+ obj.value + '</span>';
             },
//             template: "{common.icon()} <img src='/#image#.png' style='float:left; margin:3px 4px 0px 1px;'> #value#",
             url: "/roles/showTree/"
         }
     ]
}


webix.ready(function () {
    // Вывод основного представления

    webix.ui({
            cols: [
                {rows:[tb, treea] , minWidth: 400},
                {view:"resizer"},
                {rows:[mf], width: 700}

    ]});
});


</script>
</body>
</html>