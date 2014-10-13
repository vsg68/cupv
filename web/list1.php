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


var mf = {
     rows: [
        
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

var Net_Page = {
    id: "treedata",
    view: 'tree',
    select: true,
    open: true,
    // template: function(obj, com){
    //              // Подставляем свою иконку для группы
    //              // var icon = obj.$parent ? "<img src='/" + obj.image +".png' style='float:left; margin:3px 4px 0px 1px;'>" : "<div class='webix_tree_folder'></div>";
    //              return com.icon(obj, com) + '<span>'+ obj.value + '</span>';
    //          },
    url: "/bcont/getTree/",
    on: {
        "onAfterSelect": function(){
            item = this.getSelectedItem();
            if( this.data.getFirstChildId(this.getSelectedId()) ) return;
            $$('dtable').load("/badm/select/?id=" + item.id);
        }
    }
}

var Contact_Page = {
    id: "dtable",
    view:"list",
    template: function(obj){
        x = obj;
    }
    // data: []
}

webix.ready(function () {
    // Вывод основного представления

   webix.ui({
               rows:[
                 { type:"header", template:"My App!" },
                 { cols:[
                   // { view:"tree", data:tree_data, gravity:0.3, select:true },
                      Net_Page,
                      { view:"resizer" },
                      Contact_Page
                      // { view:"datatable", autoConfig:true, data:grid_data }
                 ]}
               ]
            })
});


</script>
</body>
</html>