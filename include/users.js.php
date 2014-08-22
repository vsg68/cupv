
<?php if( $permissions == $WRITE_LEVEL ): ?>

/*********   USER PAGE  ********/

var Users_UserPage = new PageAdm({
    id: "users_first",
    toolbarlabel: "Пользователи",
    hideDelButton: true,
    showSearchField: true,
    isListScroll: true,
    filterRule: function(obj,value){
            return (obj.mailbox.toLowerCase().indexOf(value) >= 0 || obj.username.toLowerCase().indexOf(value) >= 0)
    },
    addButtonClick: function(){
        return {"allow_nets": "192.168.0.0/24"}
    },
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
    formElements: [
        {view: "text", label: "mailbox", name: "mailbox"},
        {view: "text", label: "username", name: "username"},
        {view: "text", label: "password", name: "password"},
        {view: "text", label: "path", name: "path"},
        {view: "checkbox", label: "imap_enable", name: "imap_enable"},
        {view: "text", label: "allow_nets", name: "allow_nets"},
        {view: "text", label: "acl_groups", name: "acl_groups"},
        {view: "checkbox", label: "master_admin", name: "master_admin"},
        {view: "checkbox", label: "master_domain", name: "master_domain"},
        {view: "text", label: "last_login", name: "last_login", disabled: true},
        {view: "text", label: "last_ip", name: "last_ip", disabled: true},
        {view: "text", label: "last_prot", name: "last_prot", disabled: true},
        {view: "checkbox", label: "active", name: "active"},
        webix.copy(save_cancel_button),
        {}
    ],
    formRules: {
        mailbox: function (value) {
            return  checkEmail("form_users_first", value);
        },
        allow_nets: function (value) {
            return fnTestByType("nets", value);
        },
        username: webix.rules.isNotEmpty,
        password: webix.rules.isNotEmpty
    },
    list_url: '/users/showTable/',
    list_on:  {
        "onAfterSelect": function () {
            item = $$('list_users_first').getSelectedItem();
            // Закрываем все открытые формы редактирования
            $$('list_aliases_first').getParentView().back(); $$('form_fwd_first').getParentView().back(); $$('form_groups_first').getParentView().back();
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
        }
    }
});

var Aliases_UserPage = new PageAdm({
    id: "aliases_first",
    toolbarlabel: "Псевдонимы",
//    bindfield: "delivery_to",
    list_template: "<div class='isactive_#active#'>#alias_name#</div>",
    addButtonClick: function(){
        selected_User = $$("list_users_first").getSelectedItem();
        // Если не выбран пользователь - выходим
        if ( selected_User == false) return false;
        return { "alias_name" : selected_User.mailbox, "delivery_to" : selected_User.mailbox};
    },
    formElements: [
        { view: "text",label: "Псевдоним", name: "alias_name" },
        {view: "checkbox", label: "active", name: "active"},
          webix.copy(save_cancel_button),
        {}
    ],
    formRules: {
        alias_name: webix.rules.isEmail
    },
    list_on: {
        "onKeyPress": function (key) {
            Aliases_UserPage.keyPressAction(this, key);
        }
    }
});

var Fwd_UserPage = new PageAdm({
    id: "fwd_first",
    toolbarlabel: "Пересылка",
//    bindfield: "alias_name",
    list_template: "<div class='isactive_#active#'>#delivery_to#</div>",
    formElements: [
        {view: "text",label: "Псевдоним", name: "delivery_to" },
        {view: "checkbox", label: "active", name: "active"},
        webix.copy(save_cancel_button),
        {}
    ],
    formRules: {
        delivery_to: webix.rules.isEmail
    },
    list_on: { "onKeyPress": function (key) {
            Fwd_UserPage.keyPressAction(this, key);
        }
    },
    addButtonClick: function(){
        selected_User = $$("list_users_first").getSelectedItem();
        // Если не выбран пользователь - выходим
        if ( selected_User == false) return false;
        return { "alias_name" : selected_User.mailbox, "delivery_to" : selected_User.mailbox};
    }
});

var Group_UserPage = new PageAdm({
    id: "groups_first",
    toolbarlabel: "Группы",
//    bindfield: "user_id",
    list_template: "<div class='isactive_#active#'>#name#</div>",
    formElements: [
        {view: "richselect", label: "Группа", name: "name",
            options: "/groups/getGroupsList/",
            on: {
                "onChange": function(){
                    optId = $$(this.data.suggest).getMasterValue();
                    Form = this.getFormView();
                    // поле optId - ID выбранной опции
                    if( ! optId ) return false;
                    // заполняем поле guid_id при изменении select
                    Form.setValues({ group_id: $$(this.data.suggest).getList().getItem(optId).group_id},true);
                }
            }
        },
        webix.copy(save_cancel_button),
        {}
    ],
    formRules: {
        name: function (value) {
            return checkGroups("groups_first", value);
        }
    },
    list_on: { "onKeyPress": function (key) {
        Group_UserPage.keyPressAction(this, key);
        }
    },
    addButtonClick: function(){
        selected_User = $$("list_users_first").getSelectedItem();
        // Если не выбран пользователь - выходим
        if ( selected_User == false) return false;
        return { "user_id" : selected_User.id};
    }
});


/*********   ALIAS PAGE  ********/

var Aliases_AliasPage = new PageAdm({
    id: "aliases_second",
//    toolbarlabel: "Пересылка",
    isListScroll: true,
    showSearchField: true,
    filterRule: function(obj,value){
        value = value.toLowerCase();
        return (obj.alias_name.toLowerCase().indexOf(value) >= 0 || obj.delivery_to.toLowerCase().indexOf(value) >= 0);
    },
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
    formElements: [
        {view: "text",label: "Псевдоним", name: "alias_name" },
        {view: "text",label: "Пересылка", name: "delivery_to" },
        {view: "checkbox", label: "active", name: "active"},
        webix.copy(save_cancel_button),
        {}
    ],
    formRules: {
        delivery_to: webix.rules.isEmail,
        alias_name: webix.rules.isEmail
    },
    list_on: {
        "onKeyPress": function (key) {
             Aliases_AliasPage.keyPressAction(this, key);
        }
    },
    list_url: "/aliases/showTable/"
});

var Domains_AliasPage = new PageAdm({
    id: "domains_second",
    list_template: function (obj) {
        var tmpl = "<div class='fleft domain_name isactive_" + obj.active + "'  title='" + (obj.domain_notes ? obj.domain_notes : "") + "'>" + obj.domain_name + "</div>";
        tmpl += "<div class='fleft fa-exchange webix_icon domain_type_" + obj.domain_type + "' title='delivery_to: " + obj.delivery_to+ "'></div>";
        if (obj.all_enable == "1")          tmpl += "<div class='fleft fa-envelope webix_icon' title='" + obj.all_email + "'></div>";
        if (obj.relay_domain == "1")        tmpl += "<div class='fleft fa-share webix_icon' title='" + obj.relay_address + "'></div>";
        if (obj.relay_notcheckusers == "1") tmpl += "<div class='fleft fa-check webix_icon' title='check users'></div>";

        return tmpl;
    },
    list_scheme: {
        delivery_to: "virtual",
        domain_type: 0
    },
    formElements: [
        {view: "text", label: "Название", name: "domain_name" },
        {view: "text", label: "Описание", name: "domain_notes" },
        {view: "radio", label:"Тип домена", name: "domain_type", options:[{id:0,value:"Основной"},{id:1,value:"Псевдоним"},{id:2,value:"Транспорт"}],
            on: {
                onChange: function(new_value, old_value){
                    // Принудительное установление значения
                    if(old_value == undefined) return;

                    if( new_value == "0")
                        this.getFormView().setValues({"delivery_to":"virtual"},true);
                    else
                        this.getFormView().setValues({"delivery_to":""},true);
                }
            }
        },
        {view: "text", label:"Пересылка", name: "delivery_to"},
        {view: "fieldset", label:"Рассылка", body: {
            rows:[
                {view: "checkbox", label: "Рассылка.Вкл", name: "all_enable" },
                {view: "text", label: "Рассылка", name: "all_email" },
            ]
        }},
        {view: "fieldset", label:"Внешний почтовый сервер: Транспорт", body: {
            rows: [
                {view: "checkbox", label: "Вкл.Пересылку", name: "relay_domain" },
                {view: "text", label: "Пересылка", name: "relay_address" },
                {view: "checkbox", label: "Проверка польз.", name: "relay_notcheckusers"},
            ]
        }},
        {view: "checkbox", label: "Активно", name: "active"},
        webix.copy(save_cancel_button),
        {}
    ],
    formRules: {
        $obj: function(data){
            this.clearValidation();

            if( data.domain_type == 0 && data.delivery_to != "virtual") {
                webix.message({ type: "error", text: "Не заполнено поле 'Пересылка'" });
                this.elements['delivery_to'].define('css',"webix_invalid");
                return false;
            }
            else if( data.domain_type == 1 && ! chkDomainAlias("domains_second",data.delivery_to) ) {
                this.elements['delivery_to'].define('css',"webix_invalid");
                return false;
            }
            else if( data.domain_type == 2 && ! fnTestByType("transport", data.delivery_to)) {
                webix.message({ type: "error", text: "Не правильный формат поля 'Пересылка'" });
                this.elements['delivery_to'].define('css',"webix_invalid");
                return false;
            }

            if( data.all_enable == 1 && ! fnTestByType("mail",data.all_email) ) {
                webix.message({ type: "error", text: "Не правильный формат адреса рассылки" });
                this.elements['all_email'].define('css',"webix_invalid");
                return false;
            }
            if( data.relay_domain == 1 && ! fnTestByType("ip",data.relay_address) ) {
                webix.message({ type: "error", text: "Не правильный формат адреса пересылки" });
                this.elements['relay_address'].define('css',"webix_invalid");
                return false;
            }

            return true;
        }
    },
    list_on: {
        "onKeyPress": function (key) {
            Domains_AliasPage.keyPressAction(this, key);
        }
    },
    list_url: "/domains/showTable/"
});

var Groups_AliasPage = new PageTreeAdm({
    id: "groups_second",
    list_view: "tree",
    list_css: "groups",
    list_template: function(obj, com){
        // Подставляем свою иконку для группы
        var icon = obj.$parent ? com.folder(obj, com) : "<div class='webix_tree_folder'></div>";
        return com.icon(obj, com) + icon + '<span>'+ obj.value + '</span>';
    },
    formElements: [
        {view: "text", label: "Группы", name: "value" },
        {view: "checkbox", label: "Активно", name: "active"},
        webix.copy(save_cancel_button),
        {}
    ],
    formRules: {
        $obj: function(data){
            return chkUserInGroup("groups_second", data['$parent'], data['value']);
        }
    },
    formElements_rs: [
        {
            view: "richselect",
            label: "Пользователи",
            name: "value",
            options:"/users/getMailboxes",
            on: {
                "onChange": function(){
                    optId = $$(this.data.suggest).getMasterValue();
                    selected_item = $$(this.data.suggest).getList().getItem(optId);
                    // поле optId - ID выбранной опции
                    if( ! optId || selected_item == undefined) return false;
                    Form = this.getFormView();
                    // заполняем поле user_id при изменении select
                    this.getFormView().setValues({ user_id: selected_item.user_id},true);
                }
            }
        },
        webix.copy(save_cancel_button),{}
    ],
    formRules_rs: {
        $obj: function(data){
            return chkUserInGroup("groups_second",data['$parent'], data['value']);
        }
    },
    list_on: {
        "onKeyPress": function (key) {
            formId = ( this.getSelectedItem()['$parent'] ) ? "form_groups_second__rs" : "form_groups_second__txt";
            Groups_AliasPage.keyPressAction(this, key, formId);
        }
    },
    list_url: "/groups/showTree/"
});

<?php else: ?>
/*********   Users  ********/


/*********   Aliases  ********/

/*********   Forward  ********/


/*********   Groups  ********/
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
    view: "accordion",
    css:"accord",
    multi: false,
    cols:[
        {
            headerAlt:"Пользователи",
            headerHeight:0,
            header:" ",
            expand: true,
            body:{
                cols: [
                    { rows:[Users_UserPage] , gravity:5},
                    { view:"resizer"},
                    { rows:[
                            {rows: [ Aliases_UserPage ]},
                            {rows: [ Fwd_UserPage ]},
                            {rows: [ Group_UserPage ]}
                            ], gravity:3
                    }
                ]
            }
        },
        {
            headerAlt:"Псевдонимы / Рассылки / Домены",
            headerHeight:0,
            header:" ",
            collapsed: true,
            body: {
                cols: [
                    {
                        header:"Пересылка / Псевдонимы",
                        body: {
                            rows: [ Aliases_AliasPage ]
                        },
                        gravity: 2
                    },
                    {
                        header:"Группы / Пользователи",
                        body: {
                            rows: [ Groups_AliasPage ]
                        },
                        gravity: 1
                    },
                    {
                        header:"Домены",
                        body: {
                            rows: [ Domains_AliasPage ]
                        },
                        gravity: 2
                    },
                ]
            }
        },
        {
            headerAlt:"Почтовые логи",
            headerHeight:0,
            header:" ",
            collapsed: true,
            body: {
                cols: [
                    { header:"Фильтр",
                      height:30,
                      body: {rows: [ Form_LogsPage ], width: 400 }
                    },
                    { rows: [ Data_LogsPage ] }
                ]
            }
        }
    ]
};


//TODO
// 1) при создании пользователя - по окончании, переход на него
// 2) заполнение строки "пароль" - по клику на иконку
// 3) заполниние строки "сети" -  по клику на иконку (?)
// 4) экспорт в файл ....
// 5) При клике на почтовую иконку - переход на почту(www) в транскрипции user@domain*i_am@gmpro.ru
/*
При подключении:
id - min 14 знаков (bigint) такое же выставляется для всех id  в lists
 */
