
<?php if( $permissions == $WRITE_LEVEL ): ?>

/*********   USER PAGE  ********/

var ITBasePage = new PageTreeAdm({
    id: "itbase",
    list_view: "tree",
    list_css: "itbase-net",
    list_template: function(obj, com){
        // Подставляем свою иконку для группы
        if( obj.$parent )
            icon = "<div class='itbase-"+obj.tsect+" webix_tree_file'></div>";
        else
            icon = obj.open ? "<div class='webix_tree_folder_open'></div>" : "<div class='webix_tree_folder'></div>";

        return com.icon(obj, com) + icon + '<span>'+ obj.name + '</span>';
    },
    menuButtons:[
        {
            icon    : "laptop",
            label   : "New",
            click   : function() {          
                               
                                // Если кнопка нажата не на списке  - выходим, если не выделено ничего - тоже выходим
                                if (! ITBasePage.isActiveCell_List("itbase") ) {
                                    webix.message({ type: "error", text:  "Кнопки в этой области не работают" });
                                    return false;
                                }

                                tree = $$("list_itbase");
                                selected_item = tree.getSelectedItem();

                                if( selected_item == undefined ) {
                                    webix.message({ type: "error", text: "Выделите раздел, в который будем добавлять" });
                                    return false;
                                }

                                // если узел не является корнем, то ищем ID его корня
                                if( ! tree.getFirstChildId(selected_item.id) )
                                    selected_item.id = selected_item.pid ;
                                
                                defaults = {
                                            "is_new": 1, 
                                            "value" : selected_item.id, 
                                            "pid"   : selected_item.id,
                                            "tsect" : selected_item.tsect
                                        };

                                // Переход к редактированию
                                tree.select( $$("list_itbase").add( defaults, 0, selected_item.id) );
                                $$("itbase__rs").show();                                
                            }
        },
        {
            icon    : "folder-o",
            label   : "New",
            click   : function() {    
                                // какой фильтр стоит? - какое значение таббара
                                defaults = {
                                            "is_new": 1, 
                                            "pid":0, 
                                            "tsect": ($$("chPage").getValue().split("_"))[1],
                                        };

                                // Переход к редактированию
                                $$("itbase__txt").show(); 
                                $$("list_itbase").select( $$("list_itbase").add( defaults ) ); 
                           }
        },
        {
            icon    : "trash-o",
            label   : "Del",
            click   : function () {
                                // Если кнопка нажата не на списке - выходим
                                if (! ITBasePage.isActiveCell_List("itbase")) {
                                    webix.message({ type: "error", text: "Кнопки в этой области не работают" });
                                    return false;
                                }

                                var selected_item = $$("list_itbase").getSelectedItem();

                                // null если нет потомков
                                if( $$("list_itbase").data.getFirstChildId(selected_item.id) ) {
                                    webix.message({type: "error",text:"Сначала нужно удалить содержимое контейнера"});
                                    return false;
                                }

                                webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
                                    //  тут надо отослать данные на сервер
                                    if (result) {

                                        webix.ajax().post("/"+ ITBasePage.hreflink +"/delEntry/", selected_item, function (text, xml, xhr) {
                                            if (!text) {
                                                webix.message("ОK"); // server side response
                                                $$("list_itbase").remove(selected_item['id']);
                                            }
                                            else
                                                webix.message({type: "error", text: text});
                                        })
                                    }
                                }})     
                            }
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
                    options: {
                        body: {
                            url:"/itbase/RichSelect/?pid=0",
                        },
                        on: { // работает фильтрация list в элементе richselect
                            "onShow": function(){
                                selected_item = $$("list_itbase").getSelectedItem();
                                // Фильтруем значения richselect
                                $$("rs").getPopup().getList().filter("#tsect#", selected_item.tsect);
                            }
                        }
                    },
                    on: {
                        "onChange": function(){
                            optId = this.getPopup().getMasterValue();
                            selected_item = this.getPopup().getList().getItem(optId);
                            Form = this.getFormView().getValues();

                            // поле optId - ID выбранной опции
                            if( ! optId || selected_item == undefined) 
                                this.setValue(Form["$parent"]);
                            else
                            // заполняем поле user_id при изменении select
                               this.getFormView().setValues({"pid": selected_item.id },true);
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
                                              
                                                $$("list_itbase").open( values.pid );
                                                $$("list_itbase").scrollTo(0, values.id);
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
            formID = selected_item['$parent'] != 0  ? "itbase__rs" : "itbase__txt";
            ITBasePage.keyPressAction(this, key, formID);
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
    id: "itemdata",
    toolbarlabel: "",
    list_css: "itbase_data",
    // list_template: function(obj){
    //     tmpl = "";
    //     for(i = 0; i < obj["fields"].length; i++)
    //         tmpl +=  "<div class='clear'>" + 
    //                  "<div class='fleft ftype-" + obj.datatype + "'></div>" +
    //                  "<div class='fleft flabel'>" + obj["fields"][i].label + "</div>" +
    //                  "<div class='fleft fname'>" + obj["fields"][i].name + "</div>" +
    //                  "</div>";

    //     return tmpl;
    //     // "<div class='fleft datapage'>#label#:</div><div class='fleft'>#name#</div>",
    // },
    list_template: "<div class='fleft datapage'>#label#:</div><div class='fleft'>#value#</div>",
    list_on: {
        "onKeyPress": function (key) {
            // по идее может быть много типов документов
            type = this.getSelectedItem()['datatype'];
            // DataPage.keyPressAction(this, key, "itdata_" + type);   // formID = itdata_X
            DataPage.keyPressAction(this, key);
        }
    },
    // menuButtons:[
    //     {
    //         icon    : "file-text-o",
    //         label   : "New",
    //         click   : function() {          
    //                             // Если кнопка нажата не на списке  - выходим, если не выделено ничего - тоже выходим
    //                             if (! ITBasePage.isActiveCell_List("itemdata") ) {
    //                                 webix.message({ type: "error", text:  "Кнопки в этой области не работают" });
    //                                 return false;
    //                             }

    //                             defaults = {"is_new": 1, "datatype" : 1, "type": "text" };

    //                             // Переход к редактированию
    //                             $$("itdata_1").show();                                
    //                             $$("list_itemdata").add( defaults);
    //                         }
    //     },
    //     {
    //         icon    : "file-picture-o",
    //         label   : "New",
    //         click   : function() {    
    //                             // Если кнопка нажата не на списке - выходим
    //                             if (! ITBasePage.isActiveCell_List("itemdata")) {
    //                                 webix.message({ type: "error", text: "Кнопки в этой области не работают" });
    //                                 return false;
    //                             }
    //                             // какой фильтр стоит? - какое значение таббара
    //                             defaults = { "is_new": 1, "datatype":2, };

    //                             // Переход к редактированию
    //                             $$("itdata_2").show(); 
    //                             $$("list_itemdata").add( defaults ); 
    //                        }
    //     },
    //     {
    //         icon    : "trash-o",
    //         label   : "Del",
    //         click   : function () {
    //                             // Если кнопка нажата не на списке - выходим
    //                             if (! ITBasePage.isActiveCell_List("itbase")) {
    //                                 webix.message({ type: "error", text: "Кнопки в этой области не работают" });
    //                                 return false;
    //                             }

    //                             var selected_item = $$("list_itbase").getSelectedItem();

    //                             // null если нет потомков
    //                             if( $$("list_itbase").data.getFirstChildId(selected_item.id) ) {
    //                                 webix.message({type: "error",text:"Сначала нужно удалить содержимое контейнера"});
    //                                 return false;
    //                             }

    //                             webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
    //                                 //  тут надо отослать данные на сервер
    //                                 if (result) {

    //                                     webix.ajax().post("/"+ ITBasePage.hreflink +"/delEntry/", selected_item, function (text, xml, xhr) {
    //                                         if (!text) {
    //                                             webix.message("ОK"); // server side response
    //                                             $$("list_itbase").remove(selected_item['id']);
    //                                         }
    //                                         else
    //                                             webix.message({type: "error", text: text});
    //                                     })
    //                                 }
    //                             }})     
    //                         }
    //     }
    // ],
    // formPages: [
        // {
          //  formID: "itdata_1",
            formElements: [
                {view: "text",label: "Лейбл", name: "label" },
                {view: "text",label: "Название", name: "name"},
                webix.copy(save_cancel_button),{}
            ],
            formRules: {
                 name: webix.rules.isNotEmpty
            },
        // },
        // {
        //     formID: "itdata_2",
        //     formElements: [
        //         {view: "text", label: "Название", name: "name" },
        //         {
        //             view: "richselect",
        //             id: "rsdata",
        //             label: "Раздел",
        //             name: "value",
        //             options: {
        //                 body: {
        //                     url:"/itbase/RichSelect/?pid=0",
        //                 },
        //                 on: { // работает фильтрация list в элементе richselect
        //                     "onShow": function(){
        //                         selected_item = $$("list_itbase").getSelectedItem();
        //                         // Фильтруем значения richselect
        //                         $$("rsdata").getPopup().getList().filter("#tsect#", selected_item.tsect);
        //                     }
                            
        //                 }
        //             },
        //             on: {
        //                 "onChange": function(){
        //                     optId = this.getPopup().getMasterValue();
        //                     selected_item = this.getPopup().getList().getItem(optId);
        //                     Form = this.getFormView().getValues();

        //                     // поле optId - ID выбранной опции
        //                     if( ! optId || selected_item == undefined) 
        //                         this.setValue(Form["$parent"]);
        //                     else
        //                     // заполняем поле user_id при изменении select
        //                        this.getFormView().setValues({"pid": selected_item.id },true);
        //                 },
        //             }
        //         },
        //         webix.copy(save_cancel_button),{}
        //     ],
        //     formRules: {
        //           name: webix.rules.isNotEmpty
        //     },
        //     // save_form: function(){
        //     //                         var mForm = $$(this.id);
                                    
        //     //                         var values =  mForm.getValues();
                                    
        //     //                         if(values.is_new == undefined)
        //     //                             values.is_new    = 0;
                                    
        //     //                         // Сначала валидация формы - потом отправка
        //     //                         if( mForm.validate() === false ) return false;

        //     //                         $$("list_itbase").move(values.id,null,null, {parent:values.pid}); 

        //     //                         // Если не новая запись - убираем признак новой записи
        //     //                         // Важно изменить $parent после MOVE, 
        //     //                         // иначе следующее изменение будет брать не правильный парент для перемещения
        //     //                         mForm.setValues({is_new:0, "$parent":values.pid },true);

        //     //                         mForm.save();
                                    
        //     //                         webix.ajax().post("/" + ITBasePage.hreflink + "/savegroup", values,
        //     //                             function(response){
        //     //                                 if(response)
        //     //                                     webix.message({type:"error", expire: 3000, text: response}); // server side response
        //     //                                 else {
        //     //                                     webix.message("ОK"); // server side response
        //     //                                     // открываем бранч, куда переместили листок
                                              
        //     //                                     $$("list_itbase").open( values.pid );
        //     //                                     $$("list_itbase").scrollTo(0, values.id);
        //     //                                     mForm.getParentView().back();
        //     //                                 }
        //     //                             }
        //     //                         );
        //     //                     }
        // }
    // ],
   
    
});


<?php else: ?>

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
    val = "" + this.getValue().split("_")[1];
    $$("list_itbase").filter("#tsect#", val);
    // закрываются фсе формы
    $$('list_itemdata').clearAll();
    $$('list_itemdata').show();

}


/*********   USER PAGE  ********/
/******************************************** For ALL ***********************************************/
maintable = {
    // view: "accordion",
    css:"accord1",

    // multi: false,
    rows: [
        TAB,
        {
            cols:[
            // {
                // headerAlt:"Пользователи",
                // headerHeight:0,
                // header:" ",
                // expand: true,
                // body:{
                //     cols: [
                        { rows:[ITBasePage] , gravity:3},
                        { width: 12, css: "transp"},
                        { rows:[DataPage ], gravity:5,autoheight: true,}
                //     ]
                // }
            // }
            ]
        }
    ]
};


//TODO
// 2) заполнение строки "пароль" - по клику на иконку
// 3) заполниние строки "сети" -  по клику на иконку (?)
// 4) экспорт в файл ....
// 5) При клике на почтовую иконку - переход на почту(www) в транскрипции user@domain*i_am@gmpro.ru
/*
При подключении:
id - min 14 знаков (bigint) такое же выставляется для всех id  в lists
 */
