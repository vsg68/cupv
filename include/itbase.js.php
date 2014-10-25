
<?php if( $permissions == $WRITE_LEVEL ): ?>

/*********   USER PAGE  ********/

var ITBasePage = new BaseTreeAdm({
    id: "itbase",
    list_view: "tree",
    // list_css: "groups",
    list_template: "{common.icon()}{common.folder()}<span>#name#</span>",
    formElements: [
        {view: "text", label: "Значение", name: "name" },
        // {view: "text", label: "Тип записи", name: "ptype",suggest: "/itbase/getPtype" },
        webix.copy(save_cancel_button),
        {}
    ],
    // formRules: {
    //     $obj: function(data){
    //         return chkUserInGroup("groups_second", data['$parent'], data['value']);
    //     }
    // },
    formElements_rs: [
        {view: "text", label: "Название", name: "name" },
        {
            view: "richselect",
            label: "Раздел",
            name: "value",
            options:"/itbase/getSelect/?pid=0", //&tsect=",
            on: {
                "onChange": function(){

                    optId = $$(this.data.suggest).getMasterValue();
                    selected_item = $$(this.data.suggest).getList().getItem(optId);
                    Form = this.getFormView().getValues();

                    // поле optId - ID выбранной опции
                    if( ! optId || selected_item == undefined) 
                        this.setValue(Form["$parent"]);
                        // return true;
                    else
                    // заполняем поле user_id при изменении select
                       this.getFormView().setValues({"pid": selected_item.id },true);
                }
            }
        },
        webix.copy(save_cancel_button),{}
    ],
    // formRules_rs: {
    //     $obj: function(data){
    //         return chkUserInGroup("groups_second",data['$parent'], data['value']);
    //     }
    // },
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
        }
    },
    list_url: "/itbase/getTree/"
});

var DataPage = new PageAdm({
    id: "itemdata",
    toolbarlabel: "",
    list_template: "<div class='fleft datapage'>#label#:</div><div class='fleft'>#name#</div>",
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
    }
});
<?php else: ?>
/*********   USER PAGE  ********/
var DataPage = new MView({
    id: "itemdata",
    toolbarlabel: "Пользователи",
    showSearchField: true,
    showActiveOnly: true,
    filterFunction: function(){
        $$('list_users_first').filter( function(obj) {
                additionFilter = $$('chkBox_users_first').config.value;
                mainFilter     = $$('fltr_users_first').getValue().toLowerCase();
                return (obj.mailbox.toLowerCase().indexOf(mainFilter) >= 0 || obj.username.toLowerCase().indexOf(mainFilter) >= 0) && (obj.active == additionFilter);
        });
    },
    isListScroll: true,
    list_template: function (obj) {
        var tmpl = "<div class='fleft mailbox isactive_" + obj.active + "'>" + obj.mailbox + "</div>";

        tmpl += "<div class='fleft username isactive_" + obj.active + "' title='username'>" + obj.username + "</div>";

        if (obj.imap_enable == "1")
            tmpl += "<div class='fleft fa-envelope webix_icon' title='imap_enable'></div>";
        else
            tmpl += "<div class='fleft fa-envelope-o webix_icon' title='imap_disable'></div>";

        if (obj.allow_nets) {
            net = obj.allow_nets.split(',');
            for (i = 0; i < net.length; i++) {
                if (/127.0.0.1*/.test(net[i]))
                    colorclass = 'localnet';
                else if (/10.0.0.0*/.test(net[i]))
                    colorclass = 'net10';
                else
                    colorclass = 'net-' + (net[i].split('.'))[2];

                tmpl += "<div class='fleft fa-sitemap webix_icon " + colorclass + "' title='" + net[i] + "'></div>";
            }
        }

        if (obj.master_admin == "1")  tmpl += "<div class='fleft fa-male webix_icon' title='master_admin'></div>";

        if (obj.master_domain == "1") tmpl += "<div class='fleft fa-users webix_icon' title='master_domain'></div>";

        if (obj.last_login)    tmpl += "<div class='fleft last_login' title='last_login'>" + obj.last_login + "</div>";

        return tmpl;
    },
    list_url: '/users/showTable/',
    list_on:  {
        "onAfterSelect": function () {
            item = $$('list_users_first').getSelectedItem();
            // Закрываем все открытые формы редактирования
            $$('list_aliases_first').clearAll(); $$('list_fwd_first').clearAll(); $$('list_groups_first').clearAll();

            $$('list_aliases_first').load("/users/select/?q=alias&mbox=" + item.mailbox);
            $$('list_fwd_first').load("/users/select/?q=fwd&mbox=" + item.mailbox);
            $$('list_groups_first').load("/users/select/?q=group&user_id=" + item.id);
        },
        "onKeyPress": function (key) {
            Users_UserPage.keyPressAction(this, key);
        },
        "onAfterLoad": function () {
            this.config.height = (window.innerHeight - 130);
            if (window.innerWidth < 1500)  // 8-) minWidth
                this.config.width = 800;
            this.resize();
            // Фильтруем неактивные записи
            $$('list_users_first').filter("#active#",1);
        }
    }
});

var Aliases_UserPage = new MView({
    id: "aliases_first",
    toolbarlabel: "Псевдонимы",
    list_template: "<div class='isactive_#active#'>#alias_name#</div>",
    list_on: {
        "onKeyPress": function (key) {
            Aliases_UserPage.keyPressAction(this, key);
    }}
});

var Fwd_UserPage = new MView({
    id: "fwd_first",
    toolbarlabel: "Пересылка",
    list_template: "<div class='isactive_#active#'>#delivery_to#</div>",
    list_on: { "onKeyPress": function (key) {
        Fwd_UserPage.keyPressAction(this, key);
    }}
});

var Groups_UserPage = new MView({
    id: "groups_first",
    toolbarlabel: "Группы",
    list_template: "<div class='isactive_#active#'>#name#</div>",
    list_on: {
        "onKeyPress": function (key) {
        Groups_UserPage.keyPressAction(this, key);
        }
    }
});

/*********   ALIAS PAGE  ********/
var Aliases_AliasPage = new MView({
    id: "aliases_second",
    isListScroll: true,
    showSearchField: true,
    filterFunction: function(){
        $$('list_aliases_second').filter( function(obj) {
            additionFilter = $$('chkBox_aliases_second').config.value;
            mainFilter     = $$('fltr_aliases_second').getValue().toLowerCase();
            return (obj.alias_name.toLowerCase().indexOf(mainFilter) >= 0 || obj.delivery_to.toLowerCase().indexOf(mainFilter) >= 0) && (obj.active == additionFilter);
        });
    },
    showActiveOnly: true,
    list_template: function (obj) {
        var tmpl;
        tmpl = "<div class='fleft alias_name isactive_" + obj.active + "'>" + obj.alias_name + "</div>";

        icon = obj.from_username ? "fa-user" : "fa-question";
        tmpl += "<div class='fleft arrow'><div class='" + icon + " fleft webix_icon' title='" + obj.from_username + "'></div>";
        tmpl += "<div class='fleft fa-arrow-right webix_icon noborder'></div>";
        icon = obj.to_username ? "fa-user" : "fa-question";
        tmpl += "<div class='" + icon + " fleft webix_icon' title='" + obj.to_username + "'></div></div>";

        tmpl += "<div class='fleft alias_name isactive_" + obj.active + "'>" + obj.delivery_to + "</div>";

        return tmpl;
    },
    list_on: {
        "onKeyPress": function (key) {
            Aliases_AliasPage.keyPressAction(this, key);
        },
        "onAfterLoad": function () {
            $$('list_aliases_second').filter("#active#",1);
        }
    },
    list_url: "/aliases/showTable/"
});

var Domains_AliasPage = new MView({
    id: "domains_second",
    list_template: function (obj) {
        var tmpl = "<div class='fleft domain_name isactive_" + obj.active + "'  title='" + (obj.domain_notes ? obj.domain_notes : "") + "'>" + obj.domain_name + "</div>";
        tmpl += "<div class='fleft fa-truck webix_icon domain_type_" + obj.domain_type + "' title='delivery_to: " + obj.delivery_to+ "'></div>";
        if (obj.all_enable == "1")          tmpl += "<div class='fleft fa-envelope webix_icon' title='" + obj.all_email + "'></div>";
        if (obj.relay_domain == "1")        tmpl += "<div class='fleft fa-external-link webix_icon' title='relay_address:" + obj.relay_address + "'></div>";
        if (obj.relay_notcheckusers == "1") tmpl += "<div class='fleft fa-check webix_icon' title='check users'></div>";

        return tmpl;
    },
    list_on: {
        "onKeyPress": function (key) {
            Domains_AliasPage.keyPressAction(this, key);
        }
    },
    list_url: "/domains/showTable/"
});

var Groups_AliasPage = new MView({
    id: "groups_second",
    list_view: "tree",
    list_css: "groups",
    list_template: function(obj, com){
        // Подставляем свою иконку для группы
        var icon = obj.$parent ? com.folder(obj, com) : "<div class='webix_tree_folder'></div>";
        return com.icon(obj, com) + icon + '<span>'+ obj.value + '</span>';
    },
    list_url: "/groups/showTree/"
});

<?php endif; ?>

/******************************************** For ALL ***********************************************/

/*********   LOGS PAGE  ********/
var Form_LogsPage = new LogsView({
    id: "logs",
    toolbarlabel: "Фильтр поиска",
    list_view: "form",
    isHideToolbar: true,
    isDataHidden: true,
    formElements:[
        {view: "fieldset", label:"Дата поиска", body: {
            rows:[
                {view: "datepicker", label: "Начало", name: "start_date", timepicker:true,  format: "%Y-%n-%d %H:%i", value: new Date() },
                {view: "datepicker", label: "Окончание", name: "stop_date", timepicker:true,  format: "%Y-%n-%d %H:%i", value: new Date() }
            ]
        }},
        {view: "fieldset", label:"Направление", body: {
            rows: [
                {view: "radio", label:"Направление", name: "direction", value:0, options:[
                    {id:0,value:"To"},
                    {id:1,value:"From"}
                ]},
                {view: "text", label: "Адрес", name: "address" },
            ]
        }},
        {view: "fieldset", label:"Источник", body:{
            view: "select", label: "Сервер", name: "server", value:"mail", options:["mail","relay"]
        }},
        {
            id: "searchButton",
            view: "button",
            type:"iconButton",
            icon: "search",
            label: "Поиск",
            width: 90,
            click: function(){
                    var own = this;
                    own.define({disabled:true});
                    var listV = $$(Data_LogsPage.list_view + "_" + Data_LogsPage.objID);

                    listV.clearAll();
                    webix.ajax().get("/logs/show/", this.getFormView().getValues(), function (data){
                        if (data)
                            listV.parse(data);
                        else
                            listV.showOverlay("Данных нет");

                        own.define({disabled:false});
                    })
            }
        }
    ]
});

var Data_LogsPage = new LogsView({
    id: "logs",
    list_view: "datatable",
    isFormHidden: true,
    showStartButton: true,
    isListScroll: "false",
    columnConfig:[
        {id:"ReceivedAt", header:"ReceivedAt", width:150},
        {id:"SysLogTag",header:"SyslogTag", width:120,template: function(obj){
            obj.SysLogTag = obj.SysLogTag.replace("postfix\/","");
            return obj.SysLogTag;
        }},
        {id:"MSGID",header:"MsgID", width:100},
        {id:"Message",header:"Message",fillspace: true, template: function(obj){
            obj.Message = obj.Message.replace("<","&lt");
            obj.Message = obj.Message.replace(">","&gt");
            return obj.Message;
        }}
    ],
    list_on:{
        onBeforeLoad:function(){
            this.showOverlay("Loading...");
        },
        onAfterLoad:function(){
            this.hideOverlay();
        }
    },
    list_scheme:{
        $init: function(obj){

            self._nowMsgId = obj.MSGID;

            if( self._nowMsgId != self._prevMsgId) {
                self._changeClass = ! self._changeClass;
                self._prevMsgId = obj.MSGID;
            }

            if(self._changeClass)
                $$( "datatable_logs" ).addRowCss(obj.id,"even");
        }
    }
});

maintable = {
    // view: "accordion",
    css:"accord1",

    // multi: false,
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
