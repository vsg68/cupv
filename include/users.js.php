
<?php if( $permissions == $WRITE_LEVEL ): ?>

var netcolors = [];
<?= "netcolors = ".$netcolors.";"  ?>

/*********   USER PAGE  ********/

var Users_UserPage = new PageTreeAdm({
    id: "users_first",
    showTabbar  : true,
    toolbarlabel: "Пользователи",
    showSearchField: true,
    showActiveOnly: true,
    isListScroll: true,
    filterFunction: function(){
        $$('list_users_first').filter( function(obj) {
            additionFilter = $$('chkBox_users_first').config.value;
            mainFilter     = $$('fltr_users_first').getValue().toLowerCase();
            return (obj.mailbox.toLowerCase().indexOf(mainFilter) >= 0 || obj.username.toLowerCase().indexOf(mainFilter) >= 0) && (obj.active == additionFilter);
        });
    },
    list_scheme:{
        allow_nets: "192.168.0.0/24",
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
                net[i] = trim(net[i]);
                // Если запись о сети существует в базе
                colorclass = ( netcolors[net[i]] != undefined ) ? "style='color:" + netcolors[net[i]] + ";'" : "";

                tmpl += "<div class='fleft fa-sitemap webix_icon' " + colorclass + " title='" + net[i] + "'></div>";
            }
        }

        if (obj.master_admin == "1")  tmpl += "<div class='fleft fa-male webix_icon' title='master_admin'></div>";

        if (obj.master_domain == "1") tmpl += "<div class='fleft fa-users webix_icon' title='master_domain'></div>";

        if (obj.last_login)    tmpl += "<div class='fleft last_login' title='last_login'>" + obj.last_login + "</div>";

        return tmpl;
    },
    formPages: [
            {
            formElements: [
                {view: "text", label: "mailbox", name: "mailbox"},
                {view: "text", label: "username", name: "username"},
                {id:"pwd", view: "text", label: "password", name: "password",onContext:{}},
                {view: "text", label: "path", name: "path"},
                {view: "checkbox", label: "imap_enable", name: "imap_enable"},
                {view: "text", label: "allow_nets", name: "allow_nets", id:"allow_nets", popup: "nets"},
                {view: "text", label: "acl_groups", name: "acl_groups"},
                {view: "checkbox", label: "master_admin", name: "master_admin"},
                {view: "checkbox", label: "master_domain", name: "master_domain"},
                {view: "text", label: "last_login", name: "last_login", disabled: true},
                {view: "text", label: "last_ip", name: "last_ip", disabled: true},
                {view: "text", label: "last_prot", name: "last_prot", disabled: true},
                {view: "checkbox", label: "active", name: "active"},
                webix.copy(save_cancel_button),
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
            }

    }],
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
            // Фильтруем неактивные записи
            $$('list_users_first').filter("#active#",1);
        }
    }
});

var Aliases_UserPage = new PageTreeAdm({
    id: "aliases_first",
    showTabbar  : true,
    toolbarlabel: "Псевдонимы",
    list_template: "<div class='isactive_#active#'>#alias_name#</div>",
    list_EditRules: function(key){
                        if( ! $$("list_users_first").getSelectedItem() )
                                return false;
                                 
                        if( ! $$("list_aliases_first").getSelectedItem() ){
                            if( key == "Delete" || key == "Edit") 
                                return false;
                        }
                        return true;
                    },  
    list_Edit: {
                Add  : function(){
                                selected_User = $$("list_users_first").getSelectedItem();
                                defaults = {
                                             "is_new": 1, 
                                             "active"  : 1,
                                             "alias_name" : selected_User.mailbox, 
                                             "delivery_to" : selected_User.mailbox
                                            };
                                 // не показываем richselect, если кладем объект в корень
                                 $$("form_aliases_first").show();    
                                 // Переход к редактированию
                                 $$("list_aliases_first").select( $$("list_aliases_first").add(defaults) );
                            },
                Edit  : function(){ $$("form_aliases_first").show();},
                Delete: function(){
                                    var selected_id = $$("list_aliases_first").getSelectedId();
                                    webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
                                        //  тут надо отослать данные на сервер
                                        if (result) {
                                            webix.ajax().post("/aliases/delEntry/", {id: selected_id}, function (text, xml, xhr) {
                                                if (!text)
                                                    $$("list_aliases_first").remove(selected_id);
                                                else
                                                    webix.message({type: "error", text: text});
                                            })
                                        }
                                    }})   
                            },
    },                
    formPages: [
        {
            formElements: [
                { view: "text",label: "Псевдоним", name: "alias_name" },
                { view: "checkbox", label: "active", name: "active"},
                  webix.copy(save_cancel_button),
            ],
            formRules: {
                alias_name: webix.rules.isEmail
            },
    }],
    list_on: {
        "onKeyPress": function (key) {
            Aliases_UserPage.keyPressAction(this, key);
        }
    }
});

var Fwd_UserPage = new PageTreeAdm({
    id: "fwd_first",
    showTabbar  : true,
    toolbarlabel: "Пересылка",
    list_template: "<div class='isactive_#active#'>#delivery_to#</div>",
    list_EditRules: function(key){
                        if( ! $$("list_users_first").getSelectedItem() )
                                return false;
                                 
                        if( ! $$("list_fwd_first").getSelectedItem() ){
                            if( key == "Delete" || key == "Edit") 
                                return false;
                        }
                        return true;
                    },  
    list_Edit: {
                Add   : function(){
                            selected_User = $$("list_users_first").getSelectedItem();
                            defaults = {
                                         "is_new": 1, 
                                         "active"  : 1,
                                         "alias_name" : selected_User.mailbox, 
                                         "delivery_to" : selected_User.mailbox
                                        };
                            // не показываем richselect, если кладем объект в корень
                            $$("form_fwd_first").show();    
                            // Переход к редактированию
                            $$("list_fwd_first").select( $$("list_fwd_first").add(defaults) );
                        },
                Edit  : function(){ $$("form_fwd_first").show();},
                Delete: function(){
                                var selected_id = $$("list_fwd_first").getSelectedId();
                                webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
                                    //  тут надо отослать данные на сервер
                                    if (result) {
                                        webix.ajax().post("/aliases/delEntry/", {id: selected_id}, function (text, xml, xhr) {
                                            if (!text)
                                                $$("list_fwd_first").remove(selected_id);
                                            else
                                                webix.message({type: "error", text: text});
                                        })
                                    }
                                }})   
                        },
    },                
    formPages: [
            {
                formElements: [
                    {view: "text",label: "Псевдоним", name: "delivery_to" },
                    {view: "checkbox", label: "active", name: "active"},
                    webix.copy(save_cancel_button),
                ],
                formRules: {
                    delivery_to: webix.rules.isEmail
                },
            }
    ],
    list_on: { "onKeyPress": function (key) {
            Fwd_UserPage.keyPressAction(this, key);
        }
    },
});

var Groups_UserPage = new PageTreeAdm({
    id: "groups_first",
    showTabbar  : true,
    toolbarlabel: "Группы",
    list_template: "<div class='isactive_#active#'>#name#</div>",
    formPages: [
        {
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
                            return;
                        }
                    }
                },
                webix.copy(save_cancel_button),
            ],
            formRules: {
                name: function (value) {
                    return checkGroups("groups_first", value);
                }
            },
        }
    ],
    list_EditRules: function(key){
                        if( ! $$("list_users_first").getSelectedItem() )
                                return false;
                                 
                        if( ! $$("list_groups_first").getSelectedItem() ){
                            if( key == "Delete" || key == "Edit") 
                                return false;
                        }
                        return true;
                    },  
    list_Edit: {
                Add   : function(){
                            selected_User = $$("list_users_first").getSelectedItem();
                            defaults = {
                                         "is_new": 1, 
                                         "active"  : 1,
                                         "user_id" : selected_User.id,
                                        };
                            // не показываем richselect, если кладем объект в корень
                            $$("form_groups_first").show();    
                            // Переход к редактированию
                            $$("list_groups_first").select( $$("list_groups_first").add(defaults) );
                        },
                Edit  : function(){ $$("form_groups_first").show();},
                Delete: function(){
                                    var selected_id = $$("list_groups_first").getSelectedId();
                                    webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
                                        //  тут надо отослать данные на сервер
                                        if (result) {
                                            webix.ajax().post("/groups/delEntry/", {id: selected_id}, function (text, xml, xhr) {
                                                if (!text)
                                                    $$("list_groups_first").remove(selected_id);
                                                else
                                                    webix.message({type: "error", text: text});
                                            })
                                        }
                                    }})   
                        },
    }, 
    list_on: {
        "onKeyPress": function (key) {
        Groups_UserPage.keyPressAction(this, key);
        }
    },
});


/*********   ALIAS PAGE  ********/

var Aliases_AliasPage = new PageTreeAdm({
    id: "aliases_second",
    isListScroll: true,
    showSearchField: true,
    showTabbar: true,
    showActiveOnly: true,
    filterFunction: function(){
        $$('list_aliases_second').filter( function(obj) {
            additionFilter = $$('chkBox_aliases_second').config.value;
            mainFilter     = $$('fltr_aliases_second').getValue().toLowerCase();
            return (obj.alias_name.toLowerCase().indexOf(mainFilter) >= 0 || obj.delivery_to.toLowerCase().indexOf(mainFilter) >= 0) && (obj.active == additionFilter);
        },true);
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
    formPages: [
        {
            formElements: [
                {view: "text",label: "Псевдоним", name: "alias_name" },
                {view: "text",label: "Пересылка", name: "delivery_to" },
                {view: "checkbox", label: "active", name: "active"},
                webix.copy(save_cancel_button),
            ],
            formRules: {
                delivery_to: webix.rules.isEmail,
                alias_name: webix.rules.isEmail
            },
        }
    ],  
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

var Domains_AliasPage = new PageTreeAdm({
    id: "domains_second",
    list_template: function (obj) {
        var tmpl = "<div class='fleft domain_name isactive_" + obj.active + "'  title='" + (obj.domain_notes ? obj.domain_notes : "") + "'>" + obj.domain_name + "</div>";
        tmpl += "<div class='fleft fa-truck webix_icon domain_type_" + obj.domain_type + "' title='delivery_to: " + obj.delivery_to+ "'></div>";
        if (obj.all_enable == "1")          tmpl += "<div class='fleft fa-envelope webix_icon' title='" + obj.all_email + "'></div>";
        if (obj.relay_domain == "1")        tmpl += "<div class='fleft fa-external-link webix_icon' title='relay_address:" + obj.relay_address + "'></div>";
        if (obj.relay_notcheckusers == "1") tmpl += "<div class='fleft fa-check webix_icon' title='check users'></div>";

        return tmpl;
    },
    list_scheme: {
        delivery_to: "virtual",
        domain_type: 0
    },
    formPages: [
        {
            formElements: [
                {view: "text", label: "Название", name: "domain_name" },
                {view: "text", label: "Описание", name: "domain_notes" },
                {view: "radio", label:"Тип домена", name: "domain_type", options:[{id:0,value:"Основной"},{id:1,value:"Псевдоним"},{id:2,value:"Транспорт"}],
                    on: {
                        "onChange": function(new_value, old_value){
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
                        {view: "text", label: "Рассылка", name: "all_email" }
                    ]
                }},
                {view: "fieldset", label:"Внешний почтовый сервер: Транспорт", body: {
                    rows: [
                        {view: "checkbox", label: "Вкл.Пересылку", name: "relay_domain" },
                        {view: "text", label: "Пересылка", name: "relay_address" },
                        {view: "checkbox", label: "Проверка польз.", name: "relay_notcheckusers"}
                    ]
                }},
                {view: "checkbox", label: "Активно", name: "active"},
                webix.copy(save_cancel_button),
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
        }
    ],    
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
    savefunct: "savegroup",
    list_css: "groups",
    list_template: function(obj, com){
        // Подставляем свою иконку для группы
        var icon = obj.$parent ? com.folder(obj, com) : "<div class='webix_tree_folder'></div>";
        return com.icon(obj, com) + icon + '<span>'+ obj.value + '</span>';
    },
    formPages: [
        {
            formID: "groups_second__txt",
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
        },
        {
            formID: "groups_second__rs",
            formElements: [
                {
                    view: "richselect",
                    id:"rs",
                    label: "Пользователи",
                    name: "value",
                    options:"/users/getMailboxes",
                    on: {
                        "onChange": function(){
                            
                            suggestList = this.getPopup();
                            optId = suggestList.getMasterValue();
                            
                            selected_item = suggestList.getList().getItem(optId);
                            
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
            formRules: {
                $obj: function(data){
                    return chkUserInGroup("groups_second",data['$parent'], data['value']);
                }
            },
        }
    ],
    list_Edit:{
        Add_User  : function(){
                        var selected_id = $$("list_groups_second").getSelectedId();
                        var parent_id   = $$("list_groups_second").getParentId(selected_id);

                        if( parent_id )
                            selected_id = parent_id;

                        defaults = {"value":"", "is_new":1};    // Дефолтные значения

                        // Переход к редактированию
                        $$("groups_second__rs").show();
                        $$("list_groups_second").select( $$("list_groups_second").add(defaults, 0, selected_id) );
                    },
        Add_Group: function(){
                        defaults = {"is_new": 1,"active": 1};

                        // Переход к редактированию
                        $$("groups_second__txt").show();
                        $$("list_groups_second").select( $$("list_groups_second").add(defaults) );
                    },
        Edit      : function(){
                        if( $$("list_groups_second").getSelectedItem()['$parent'] )
                            $$("groups_second__rs").show()
                        else
                            $$("groups_second__txt").show(); 
                    },
        Delete    : function(){
                        var selected_item = $$("list_groups_second").getSelectedItem();

                        webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
                            //  тут надо отослать данные на сервер
                            if (result) {

                                webix.ajax().post("/groups/delEntry/", {id: selected_item['id'], group_id: selected_item['$parent']}, function (text, xml, xhr) {
                                    if (!text) {
                                        webix.message("ОK"); // server side response
                                        $$("list_groups_second").remove(selected_item['id']);
                                    }
                                    else
                                        webix.message({type: "error", text: text});
                                })
                            }
                        }})   
                    },
    },    
    list_EditRules: function(key){
        var selected_item = $$("list_groups_second").getSelectedItem();
         
        if( !selected_item ){
            if( key == "Delete" || key == "Edit" || key == "Add_User") 
                return false;
        }
        else {
            if( selected_item['$count'] && key == "Delete" )
                return false;
        }
        return true;
    }, 
    list_on: {
        "onKeyPress": function (key) {
            formId = ( this.getSelectedItem()['$parent'] ) ? "groups_second__rs" : "groups_second__txt";
            Groups_AliasPage.keyPressAction(this, key, formId);
        }
    },
    list_url: "/groups/showTree/"
});

<?php else: ?>
/*********   USER PAGE  ********/
var Users_UserPage = new MView({
    id: "users_first",
    showTabbar  : true,
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
            // Фильтруем неактивные записи
            $$('list_users_first').filter("#active#",1);
        }
    }
});

var Aliases_UserPage = new MView({
    id: "aliases_first",
    showTabbar  : true,
    toolbarlabel: "Псевдонимы",
    list_template: "<div class='isactive_#active#'>#alias_name#</div>",
    list_on: {
        "onKeyPress": function (key) {
            Aliases_UserPage.keyPressAction(this, key);
    }}
});

var Fwd_UserPage = new MView({
    id: "fwd_first",
    showTabbar  : true,
    toolbarlabel: "Пересылка",
    list_template: "<div class='isactive_#active#'>#delivery_to#</div>",
    list_on: { "onKeyPress": function (key) {
        Fwd_UserPage.keyPressAction(this, key);
    }}
});

var Groups_UserPage = new MView({
    id: "groups_first",
    showTabbar  : true,
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
    list_view: "form",
    isHideToolbar: true,
    formElements:[
        {view: "fieldset", label:"Дата поиска", body: {
            rows:[
                {view: "datepicker", label: "Начало", name: "start_date", timepicker:true, format: "%Y-%n-%d %H:%i", value: new Date() },
                {view: "datepicker", label: "Окончание", name: "stop_date", timepicker:true, format: "%Y-%n-%d %H:%i", value: new Date() }
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

                    $$("datatable_dt").clearAll();
                    webix.ajax().get("/logs/show/", this.getFormView().getValues(), function (data){
                        if (data) 
                            $$("datatable_dt").parse(data);
                        else
                            $$("datatable_dt").showOverlay("Данных нет");

                        own.define({disabled:false});
                    })
            }
        },
        {}
    ]
});

var Data_LogsPage = new LogsView({
    id: "dt", //  id ->  this.list_view + "_" + this.objID,
    list_view: "datatable",
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
        "$init": function(obj){

            self._nowMsgId = obj.MSGID;

            if( self._nowMsgId != self._prevMsgId) {
                self._changeClass = ! self._changeClass;
                self._prevMsgId = obj.MSGID;
            }

            if(self._changeClass)
                $$( "datatable_dt" ).addRowCss(obj.id,"even");
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
                    { rows:[Users_UserPage] , gravity:5, minWidth:800},
                    { view:"resizer"},
                    { rows:[
                            {rows: [ Aliases_UserPage ]},
                            {rows: [ Fwd_UserPage ]},
                            {rows: [ Groups_UserPage ]}
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
                      // height:30,
                      body: {
                        rows: [ Form_LogsPage ],
                        width: 400 
                      }
                    },
                    { rows: [ Data_LogsPage ] }
                ]
            }
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
