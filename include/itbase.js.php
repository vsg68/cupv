
<?php if( $permissions == $WRITE_LEVEL ): ?>

/*********   USER PAGE  ********/

var ITBasePage = new BaseTreeAdm({
    id: "itbase",
    list_view: "tree",
    // list_css: "groups",
    list_template: "{common.icon()}{common.folder()}<span>#name#</span>",
    formElements: [
        {view: "text", label: "Значение", name: "name" },
        webix.copy(save_cancel_button),{}
    ],

    formRules: {
         name: webix.rules.isNotEmpty
    },

    formElements_rs: [
        {view: "text", label: "Название", name: "name" },
        {
            view: "richselect",
            label: "Раздел",
            name: "value",
            options:"/itbase/getSelect/?pid=0",
            on: {
                "onChange": function(){
                    // x = this.getFormView().getValues().tsect;
                    // y = $$(this.data.suggest).define("filter", function(value){
                    //     "#tsect#",x});
                    // // .filter("#tsect#",x);
            
                    optId = $$(this.data.suggest).getMasterValue();
                    selected_item = $$(this.data.suggest).getList().getItem(optId);
                    Form = this.getFormView().getValues();

                    // поле optId - ID выбранной опции
                    if( ! optId || selected_item == undefined) 
                        this.setValue(Form["$parent"]);
                    else
                    // заполняем поле user_id при изменении select
                       this.getFormView().setValues({"pid": selected_item.id },true);
                }
            }
        },
        webix.copy(save_cancel_button),{}
    ],

    formRules_rs: {
          name: webix.rules.isNotEmpty
    },

    list_on: {
        "onKeyPress": function (key) {
            formId = ( this.getSelectedItem()['$parent'] ) ? "form_itbase__rs" : "form_itbase__txt";
            ITBasePage.keyPressAction(this, key, formId);
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
        }
    },

    list_url: "/itbase/getTree/"
});

var DataPage = new PageAdm({
    id: "itemdata",
    toolbarlabel: "",
    list_css: "itbase_data",
    list_template: function(obj){
        tmpl = "";
        for(i = 0; i < obj["fields"].length; i++)
            tmpl +=  "<div class='clear'>" + 
                     "<div class='fleft " + obj.type + "'></div>" +
                     "<div class='fleft flabel'>" + obj["fields"][i].label + "</div>" +
                     "<div class='fleft fname'>" + obj["fields"][i].name + "</div>" +
                     "</div>";

        return tmpl;
        // "<div class='fleft datapage'>#label#:</div><div class='fleft'>#name#</div>",
    },
    // addButtonClick: function(){
    //     selected_User = $$("list_users_first").getSelectedItem();
    //     // Если не выбран пользователь - выходим
    //     if ( selected_User == false) return false;
    //     return { "alias_name" : selected_User.mailbox, "delivery_to" : selected_User.mailbox};
    // },
    // formElements: [
    //     { view: "text",label: "Псевдоним", name: "alias_name" },
    //     {view: "checkbox", label: "active", name: "active"},
    //       webix.copy(save_cancel_button),
    //     {}
    // ],
    // formRules: {
    //     alias_name: webix.rules.isEmail
    // },
    list_on: {
        "onKeyPress": function (key) {
            DataPage.keyPressAction(this, key);
        }
    },
    
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
