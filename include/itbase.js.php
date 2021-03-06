
<?php if( $permissions == $WRITE_LEVEL ): ?>

/*********   USER PAGE  ********/

var ITBasePage = new PageTreeAdm({
    id: "itbase",
    list_view: "tree",
    list_template: function(obj, com){
        // Подставляем свою иконку для группы
        if( obj.fldr == "0" )
            icon = "<div class='itbase-"+obj.tsect+" webix_tree_file'></div>";
        else
            icon = obj.open ? "<div class='itbase-"+obj.tsect+" webix_tree_folder_open'></div>" : "<div class='itbase-"+obj.tsect+" webix_tree_folder'></div>";

        return com.icon(obj, com) + icon + '<span>'+ obj.name + '</span>';
    },
    list_Edit:{
        Add_Item  : function(){
                                 selected_item = $$("list_itbase").getSelectedItem() || 
                                            { "id":0, "pid":0, "tsect": $$("chPage").getValue().split("_")[1] };

                                 // если узел не является корнем, то ищем ID его корня
                                 if( selected_item["fldr"] == 0 && selected_item.pid != 0 )
                                     selected_item.id = selected_item.pid ;
                                 
                                 defaults = {
                                             "is_new": 1, 
                                             "value" : selected_item.id, 
                                             "pid"   : selected_item.id,
                                             "fldr"  : 0,
                                             "tsect" : selected_item.tsect
                                         };

                                 // Переход к редактированию
                                 $$("list_itbase").select( $$("list_itbase").add( defaults, 0, selected_item.id) );

                                 // не показываем richselect, если кладем объект в корень
                                 $$("itbase__rs").show();     
                    },
        Add_Folder: function(){
                             defaults = {
                                         "is_new": 1, 
                                         "pid":0, 
                                         "fldr":1, 
                                         "tsect": ($$("chPage").getValue().split("_"))[1],
                                     };

                             // Переход к редактированию
                             $$("itbase__txt").show(); 
                             $$("list_itbase").select( $$("list_itbase").add( defaults ) );   
                    },
        Delete    : function(){
                            var selected_item = $$("list_itbase").getSelectedItem();
                            
                            if( !selected_item )
                                return webix.message({type : "error",text:"Выделите объект"});

                            if( $$("list_itbase").isBranch(selected_item['id']) ) 
                              return  webix.message({type: "error",text:"Сначала нужно удалить содержимое контейнера"});

                            webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
                                //  тут надо отослать данные на сервер
                                if (result) {
                                    webix.ajax().post("/"+ ITBasePage.hreflink +"/delEntry/", selected_item, function (text, xml, xhr) {
                                        if (!text) {
                                            webix.message("ОK"); // server side response
                                            $$("list_itbase").remove(selected_item['id']);
                                            $$("list_iemdata").clearAll();
                                        }
                                        else
                                            webix.message({type: "error", text: text});
                                    })
                                }
                            }})     
                    },
        Copy      : function(){

                            var selected_item = $$("list_itbase").getSelectedItem();

                            if( !selected_item )
                               return webix.message({type : "error",text:"Выделите объект"});

                            if( selected_item["fldr"] == "1" ) 
                               return  webix.message({type : "error",text:"Копирются только объекты"});

                            defaults = {
                                            "is_new"  : 1, 
                                            "copy_id" : selected_item.id, 
                                            "name"    : selected_item.name + "_copy", 
                                            "value"   : selected_item.pid, 
                                            "pid"     : selected_item.pid,
                                            "fldr"    : selected_item.fldr,
                                            "tsect"   : selected_item.tsect
                                        };

                            //  делаем новую запись
                            $$("list_itbase").select( $$("list_itbase").add( defaults, 0, selected_item.pid) );
                            
                            $$("itbase__rs").show();   
                    },
        Edit      : function(){
                            if( $$("list_itbase").getSelectedItem()["fldr"] == 1) 
                                $$("itbase__txt").show();

                            if( $$("list_itbase").getSelectedItem()["fldr"] == 0)
                                $$("itbase__rs").show();
                    },
    },             
    menuButtons:[
        {
            icon : "laptop",
            label: "New",
            click: function() {          
                             // Если кнопка нажата не на списке  - выходим, если не выделено ничего - тоже выходим
                             if( ! ITBasePage.isActiveCell_List() )
                                return false;

                             // Присваиваем дефолтные значения если ничего не выделено
                             selected_item = $$("list_itbase").getSelectedItem() || { "id":0, "pid":0, "tsect": $$("chPage").getValue().split("_")[1] };

                             // если узел не является корнем, то ищем ID его корня
                             if( selected_item["$count"] == 0 && selected_item.pid != 0 )
                                 selected_item.id = selected_item.pid ;
                             
                             defaults = {
                                         "is_new": 1, 
                                         "value" : selected_item.id, 
                                         "pid"   : selected_item.id,
                                         "fldr"  : 0,
                                         "tsect" : selected_item.tsect
                                     };

                             // Переход к редактированию
                             $$("list_itbase").select( $$("list_itbase").add( defaults, 0, selected_item.id) );

                             // не показываем richselect, если кладем объект в корень
                             $$("itbase__rs").show();                                
                         },
        },
        {
            icon : "folder-o",
            label: "New",
            click: function() {    
                            if( ! ITBasePage.isActiveCell_List() )
                                return false;
                             // какой фильтр стоит? - какое значение таббара
                             defaults = {
                                         "is_new": 1, 
                                         "pid":0, 
                                         "fldr":1, 
                                         "tsect": ($$("chPage").getValue().split("_"))[1],
                                     };

                             // Переход к редактированию
                             $$("itbase__txt").show(); 
                             $$("list_itbase").select( $$("list_itbase").add( defaults ) ); 
                        }
        },
        {
            icon :    "copy",
            label:    "Copy",
            isEnable: function(){
                             item = $$("list_itbase").getSelectedItem();
                             if( item.fldr == "1" ) 
                                 webix.message({type : "error",text:"Копирются только объекты"});

                             return ( item.fldr != "1" );
            }, 
            click:    function(){
                            // Если кнопка нажата не на списке - выходим
                            if( ! (this.config.isEnable() && ITBasePage.isActiveCell_List()) )
                                return false;
                             
                             var selected_item   = $$("list_itbase").getSelectedItem();
                             
                             defaults = {
                                             "is_new"  : 1, 
                                             "copy_id" : selected_item.id, 
                                             "name"    : selected_item.name + "_copy", 
                                             "value"   : selected_item.pid, 
                                             "pid"     : selected_item.pid,
                                             "fldr"    : selected_item.fldr,
                                             "tsect"   : selected_item.tsect
                                         };

                             //  делаем новую запись
                             $$("list_itbase").select( $$("list_itbase").add( defaults, 0, selected_item.pid) );
                             
                             $$("itbase__rs").show();  
                         },
        }, 
        {
            icon :    "trash-o",
            label:    "Del",
            isEnable: function(){
                         id = $$("list_itbase").getSelectedId();
                         if( $$("list_itbase").isBranch(id) ) 
                            webix.message({type: "error",text:"Сначала нужно удалить содержимое контейнера"});
                         return ! $$("list_itbase").isBranch(id);
                         
            },  
            click:    function() {
                            // Если кнопка нажата не на списке - выходим
                            if( ! (this.config.isEnable() && ITBasePage.isActiveCell_List()) )
                                return false;

                             var selected_item = $$("list_itbase").getSelectedItem();

                             webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
                                 //  тут надо отослать данные на сервер
                                 if (result) {

                                     webix.ajax().post("/"+ ITBasePage.hreflink +"/delEntry/", selected_item, function (text, xml, xhr) {
                                         if (!text) {
                                             webix.message("ОK"); // server side response
                                             $$("list_itbase").remove(selected_item['id']);
                                             $$("list_iemdata").clearAll();
                                         }
                                         else
                                             webix.message({type: "error", text: text});
                                     })
                                 }
                             }})     
                         },
        }
    ],
    formPages: [
        {
            formID: "itbase__txt",
            formElements: [
                {view: "text", label: "Значение", name: "name" },
                webix.copy(save_cancel_button),{}
            ],
            formRules: {
                 name: webix.rules.isNotEmpty
            },
        },
        {
            formID: "itbase__rs",
            formElements: [
                {view: "text", label: "Название", name: "name" },
                {
                    view: "richselect",
                    id: "rs",
                    label: "Раздел",
                    name: "value",
                    options: {},
                    on: {
                        "onChange": function(){
                            optId = this.getPopup().getMasterValue();
                            Form = this.getFormView().getValues();
                            // поле optId - ID выбранной опции - делаем строковое значение
                            if(  ! optId  ) 
                                this.setValue("" + Form["$parent"]);
                            else
                            // заполняем поле user_id при изменении select
                                this.getFormView().setValues({"pid": Form["value"] },true);
                        },
                        
                    }
                },

                webix.copy(save_cancel_button),{}
            ],
            formRules: {
                  name: webix.rules.isNotEmpty
            },
            save_form: function(){
                                    var mForm = $$(this.id);
                                    
                                    var values =  mForm.getValues();
                                   
                                    if(values.is_new == undefined)
                                        values.is_new    = 0;
                                    
                                    var is_reload = values.is_new ? true : false;

                                    // Сначала валидация формы - потом отправка
                                    if( mForm.validate() === false ) return false;

                                    $$("list_itbase").move(values.id,null,null, {parent:values.pid}); 

                                    // Если не новая запись - убираем признак новой записи
                                    // Важно изменить $parent после MOVE, 
                                    // иначе следующее изменение будет брать не правильный парент для перемещения
                                    mForm.setValues({is_new:0, "$parent":values.pid },true);

                                    mForm.save();
                                    
                                    webix.ajax().post("/" + ITBasePage.hreflink + "/savegroup", values,
                                        function(response){
                                            if(response)
                                                webix.message({type:"error", expire: 3000, text: response}); // server side response
                                            else {
                                                webix.message("ОK"); // server side response
                                                // открываем бранч, куда переместили листок
                                              
                                                values.pid == "0" || $$("list_itbase").open( values.pid );

                                                $$("list_itbase").scrollTo(0, values.id);
                                                // при редактировании записи - показываем данные
                                                !is_reload  || $$('list_itemdata').load("/itbase/select/?pid=" + values.id);
                                                
                                                mForm.getParentView().back();
                                            }
                                        }
                                    );
                                }
        }
    ],
    list_on: {
        "onKeyPress": function (key) {
            selected_item = this.getSelectedItem();
            formID = selected_item.fldr == "0"  ? "itbase__rs" : "itbase__txt";
            ITBasePage.keyPressAction(this, key, formID);
        },
        "onAfterSelect": function () {
            item = $$('list_itbase').getSelectedItem();
            
            // Закрываем все открытые формы редактирования
            $$('list_itemdata').getParentView().back(); 
            $$('list_itemdata').clearAll();

            // count != 0 - значит это папка, 
            if( item['$count'] ) return false;

            $$('list_itemdata').load("/itbase/select/?pid=" + item.id);
            // Заполняем селект в форме     
            selectOpt = $$("rs").getPopup().getList();
            selectOpt.clearAll();
            selectOpt.add({"id": "0", "value":"-root-"},0);
            selectOpt.load("/itbase/RichSelect/?tsect="+item.tsect);
        },
        "onItemClick": function(id){

            tree = $$('list_itbase');
            item = tree.getItem(id);
            
            // если потомков нет, то и говорить не о чем :)
            if( ! item['$count'] ) return true;
            
            if( tree.isBranchOpen(id) )
                tree.close(id);
            else
                tree.open(id);
        },
        "onAfterLoad": function () {
            // Фильтруем записи для второй вкладки
            $$('list_itbase').filter("#tsect#","0");
        },
    },

    list_url: "/itbase/getTree/"
});

var DataPage = new PageTreeAdm({
    id           : "itemdata",
    hreflink     : "itbase",
    toolbarlabel : "",
    list_css     : "itbase_data",
    list_template: function(obj){
        value = (obj.secure == "1") ? "<div class='fleft webix_icon fa-key'></div>" : "<div class='fleft'>"+ obj.value +"</div>";
        return "<div class='fleft datapage'>" + obj.label +":</div>" + value;
    },
    menuButtons:[
        {
            icon : "plus",
            label: "New",
            isEnable: function(){   
                // true - работают
                return $$("list_itbase").getSelectedItem().fldr != "1";
            },
            click: function() {          
                            // Если кнопка нажата не на списке  - выходим, если не выделено ничего - тоже выходим
                            if( ! (this.config.isEnable() && DataPage.isActiveCell_List() ) ) 
                               return false;
                            selected_item   = $$("list_itbase").getSelectedItem();
                            defaults = {
                                        is_new  : 1,
                                        datatype: selected_item.tsect,
                                        pid     : selected_item.id,
                                    };
                            $$("form_itemdata").show();
                            // создаем новую запись
                            $$("list_itemdata").select( $$("list_itemdata").add(defaults) );
                             
                         },
        },
        {
            icon :    "trash-o",
            label:    "Del",
            isEnable: function(){
                         return $$("list_itemdata").getSelectedId();
            },  
            click:    function() {
                            // Если кнопка нажата не на списке - выходим
                            if( ! (this.config.isEnable() && DataPage.isActiveCell_List()) )
                                return false;

                             var selected_item = $$("list_itemdata").getSelectedItem();

                             webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
                                 //  тут надо отослать данные на сервер
                                 if (result) {
                                     webix.ajax().post("/"+ DataPage.hreflink +"/delStr/", selected_item, function (text, xml, xhr) {
                                         if (!text) {
                                             webix.message("ОK"); // server side response
                                             $$("list_itemdata").remove(selected_item['id']);
                                         }
                                         else
                                             webix.message({type: "error", text: text});
                                     })
                                 }
                             }})     
                         },
        },
    ],
    formPages: [
        {
            formElements: [
                {view: "text",label: "Лейбл", name: "label" },
                {view: "text",label: "Название", name: "value"},
                {view: "checkbox",label: "Скрыто", name: "secure"},
                webix.copy(save_cancel_button),{}
            ],
            formRules: {
                 label: webix.rules.isNotEmpty,
                 value: webix.rules.isNotEmpty
            },
            save_form: function(){
                                var mForm = $$(this.id);

                                var values =  mForm.getValues();

                                if(values.is_new == undefined)
                                    values.is_new = 0;

                                if ( mForm.save() === false)  return false;

                                // Если не новая запись - убираем признак новой записи
                                mForm.setValues({is_new:0},true);

                                webix.ajax().post("/itbase/save/", values, function(response){
                                                                                if(response)
                                                                                    webix.message({type:"error", expire: 3000, text: response}); // server side response
                                                                                else {
                                                                                    webix.message("ОK"); // server side response
                                                                                    mForm.getParentView().back();
                                                                                    $$("list_itemdata").showItem(values.id);
                                                                                }
                                        });
                                },
        }
    ],
    list_on: {
        "onKeyPress": function (key) {
            DataPage.keyPressAction(this, key);
        }
    },
    // addButtonClick: function(){
    //     selected_item = $$("list_itbase").getSelectedItem();
    //     // Если не выбран пользователь - выходим
    //     if ( selected_item == false) return false;

    //     if( $$("list_itbase").isBranch(selected_item.id) )
    //         webix.message({type:"error", text:"Выделите объект"});

    //     return { "pid": selected_item.id, "datatype": 1, "ftype": "text",};
    // }
});


<?php else: ?>

var ITBasePage = new MView({
    id: "itbase",
    list_view: "tree",
    list_css: "itbase-net",
    list_template: function(obj, com){
        // Подставляем свою иконку для группы
        if( obj.$parent || obj.tsect == 1)
            icon = "<div class='itbase-"+obj.tsect+" webix_tree_file'></div>";
        else
            icon = obj.open ? "<div class='webix_tree_folder_open'></div>" : "<div class='webix_tree_folder'></div>";

        return com.icon(obj, com) + icon + '<span>'+ obj.name + '</span>';
    },
    list_on: {
        "onKeyPress": function (key) {
            ITBasePage.keyPressAction(this, key);
        },
        "onAfterSelect": function () {
            item = $$('list_itbase').getSelectedItem();
            // count != 0 - значит это папка, 
            if( item['$count'] ) return false;

            // Закрываем все открытые формы редактирования
            $$('list_itemdata').getParentView().back(); 
            $$('list_itemdata').clearAll();

            $$('list_itemdata').load("/itbase/select/?pid=" + item.id);
        },
        "onItemClick": function(id){
            tree = $$('list_itbase');
            item = tree.getItem(id);
            
            // если потомков нет, то и говорить не о чем :)
            if( ! item['$count'] ) {
                $$('list_itemdata').clearAll();             
                return true;
            }

            if( tree.isBranchOpen(id) )
                tree.close(id);
            else
                tree.open(id);
        },
        "onAfterLoad": function () {
            // Фильтруем записи для второй вкладки
            $$('list_itbase').filter("#tsect#","0");
        },
    },
    list_url: "/itbase/getTree/"
});

var DataPage = new MView({
    id           : "itemdata",
    hreflink     : "itbase",
    toolbarlabel : "",
    list_css     : "itbase_data",
    list_template: function(obj){
        value = (obj.ftype  == "password") ? "<dev class='webix-icon fa-key'></dev>" : obj.value;
        return "<div class='fleft datapage'>" + obj.label +":</div><div class='fleft'>"+ value +"</div>";
    },
    list_on: {
        "onKeyPress": function (key) {
            DataPage.keyPressAction(this, key);
        }
    },
});

<?php endif; ?>

var TAB = {view:"tabbar", id:"chPage", click:"getOptionTab", value: "sect_0", options: [ 
                { value: "<span class='webix_icon fa-sitemap'></span>Сеть", id:"sect_0",width:150 },
                { value: "<span class='webix_icon fa-book'></span>Контакты", id:"sect_1",width:150 },
                { value: "<span class='webix_icon fa-phone'></span>Телeфоны", id:"sect_2",width:150 },
               ],
           minWidth:400, 
           css: "itbase_tabs"  
           
    };

function getOptionTab() {
    var val = "" + this.getValue().split("_")[1];
    // В листе присутствует удаленное поле, и если это не обработать, 
    // получаем ошибку т.k. obj == undefined
    // $$("list_itbase").filter(function(obj){
    //      return ( obj == undefined) ? false : obj.tsect == val;
    // });
    // закрываются фсе формы
    $$("list_itbase").filter("tsect",val);  // работает для версии 1.10
    $$("list_itemdata").clearAll();
    $$("list_itemdata").show();
    $$("list_itbase").show();
}


/*********   USER PAGE  ********/
/******************************************** For ALL ***********************************************/
maintable = {
    // view: "accordion",
    // css:"accord1",
    // multi: false,
    rows: [
        TAB,
        {
            cols:[
                { rows:[ITBasePage] , gravity:3},
                { width: 12, css: "transp"},
                { rows:[DataPage ], gravity:5,autoheight: true,}
            ]
        }
    ]
};

