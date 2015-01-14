
<?php if( $permissions == $WRITE_LEVEL ): ?>

/*********   USER PAGE  ********/

var SectPage = new PageTreeAdm({
    id           : "admin_sect",
    list_template: function (obj) {
        var tmpl = "<div class='fleft syslogtag isactive_" + obj.active + "'>" + obj.name + "</div>";

        tmpl += "<div class='fleft fa-link webix_icon' title='http://<?= $_SERVER["SERVER_NAME"] ?>/" + obj.link + "'></div>";
        tmpl += "<div class='fleft  isactive_" + obj.active + " '>" + obj.note + "</div>";
        return tmpl;
    },
    formPages: [{
        formElements: [
            {view: "text", label: "Название", name: "name"},
            {view: "text", label: "Описание", name: "note"},
            {view: "richselect", label: "http", name: "link", options: "/admin/get_ctrl" },
            {view: "checkbox", label: "active", name: "active"},
            webix.copy(save_cancel_button),
        ],
        formRules: {
            name: webix.rules.isNotEmpty,
            link: webix.rules.isNotEmpty
        },
    }],
    list_url: '/admin/sections/',
    list_on:  {
        "onKeyPress": function (key) {
            SectPage.keyPressAction(this, key);
        },
        "onAfterLoad": function () {
            this.config.height = (window.innerHeight - 170);
            this.resize();
        }
    }
});

var Roles_Page = new PageTreeAdm({
    id           : "roles",
    list_css     : "roles",
    list_template: function(obj){
        var tmpl = "<div class='fleft roles isactive_" + obj.active + "'>" + obj.name + "</div>";
            tmpl += "<div class='fleft isactive_" + obj.active + "'>" + obj.note + "</div>";
        return tmpl;
    },
    formPages: [{
                formElements: [
                    {view: "text", label: "Роль", name: "name" },
                    {view: "text", label: "Описание", name: "note" },
                    {view: "checkbox", label: "Активно", name: "active"},
                    webix.copy(save_cancel_button),
                    {}
                ],
                formRules: {
                    name: function (value) {
                        return chkDublRoles("roles", value);
                    },
                    name: webix.rules.isNotEmpty,
                }
    }],
    list_on: {
        "onKeyPress": function (key) {
            Roles_Page.keyPressAction(this, key);
        },
        "onAfterSelect": function () {
            item = $$('list_roles').getSelectedItem();
            // Закрываем все открытые формы редактирования
            $$('list_roles_rights').getParentView().back();
            $$('list_roles_rights').clearAll();

            $$('list_roles_rights').load("/roles/select/?id=" + item.id);
        },
    },
    list_url: "/roles/showTable/"
});

var Rights_Page = new PageTreeAdm({
    id           : "roles_rights",
    savefunct    : "saveRights",
    list_template: "<div class='fleft permission permission_#slname#' title='#slname#'></div><div class='fleft'>#sectname#</div>",
    formPages: [{
                formElements: [
                    {view: "richselect", label: "", name: "slname", 
                        options: "/roles/getSlevel",
                        on: {
                            "onChange": function(){
                                optId = $$(this.data.suggest).getMasterValue();
                                Form = this.getFormView();
                                // поле optId - ID выбранной опции
                                if( ! optId ) return false;
                                // заполняем поле guid_id при изменении select
                                Form.setValues({ slid: $$(this.data.suggest).getList().getItem(optId).slid},true);
                                return;
                            }
                        }
                    },
                    webix.copy(save_cancel_button),
                ],
    }],
    list_Edit: {
        Edit  : function(){ $$("form_roles_rights").show();}
    },
    list_on: {
        "onKeyPress": function (key) {
            Rights_Page.keyPressAction(this, key);
        },
        "onAfterSelect": function () {
           item = $$('list_roles_rights').getSelectedItem();
           $$('form_roles_rights').getChildViews()[0].define('label',item.sectname);
        }
    }
});

var Auth_Page = new PageTreeAdm({
    id           : "auth",
    list_template: "<div class='fleft username isactive_#active#' title='#login#'>#note#</div><div class='fleft' title='Роль'>#name#</div>",
    formPages: [{
                formElements: [
                    {view: "text", label: "Логин", name: "login"},
                    {view: "text", label: "Пользователь", name: "note"},
                    {view: "text", label: "Пароль", type: 'password', name: "passwd"},
                    {view: "richselect", label: "Роль", name: "name", 
                        options: "/roles/getRoles",
                        on: {
                            "onChange": function(){
                                optId = $$(this.data.suggest).getMasterValue();
                                Form = this.getFormView();
                                // поле optId - ID выбранной опции
                                if( ! optId ) return false;
                                // заполняем поле guid_id при изменении select
                                Form.setValues({ role_id: $$(this.data.suggest).getList().getItem(optId).rid},true);
                                return;
                            }
                        }
                    },
                    {view: "checkbox", label: "Активно", name: "active"},
                      webix.copy(save_cancel_button),
                ],
                formRules: {
                    login: webix.rules.isNotEmpty,
                    passwd: function(obj){
                              if( obj.is_new == 1 && obj.passwd == "") {
                                  webixю.message({type: 'error', text: 'Задайте пароль пользователю'});
                                  return false;
                              }
                              return true;
                    },
                },
    }],
    list_url: '/auth/showTable/',
    list_on: {
        "onKeyPress": function (key) {
            Auth_Page.keyPressAction(this, key);
        },
    }
});

var Nets_Page = new PageTreeAdm({
    id           : "nets",
    list_template: "<div class='fleft webix_icon fa-sitemap' style='color:#color#'></div><div class='fleft alias_name isactive_#active#'>#net#/#mask#</div><div class='fleft isactive_#active#'> #note#</div>",
    list_scheme:{
        color: "#dddddd",
        mask : 24,
    },
    formPages: [{
        formElements: [
            {view: "text", label: "Сеть", name: "net"},
            {view: "counter", label: "Маска", name: "mask", min:16, max:32, step: 1},
            {view: "text", label: "Описание", name: "note"},
            {view: "colorpicker", label: "Цвет", name: "color"},
            {view: "checkbox", label: "active", name: "active"},
            webix.copy(save_cancel_button),
        ],
        formRules: {
            net: function (value) {
                    return fnTestByType("ip", value);
                },
        },
    }],
    list_url: '/nets/showTable/',
    list_on:  {
        "onKeyPress": function (key) {
            Nets_Page.keyPressAction(this, key);
        },
    }
});

<?php else: ?>
/*********   USER PAGE  ********/
var SectPage = new MView({
    id: "admin_sect",
    list_template: function (obj) {
        var tmpl = "<div class='fleft syslogtag isactive_" + obj.active + "'>" + obj.name + "</div>";

        tmpl += "<div class='fleft fa-link webix_icon' title='http://<?= $_SERVER["SERVER_NAME"] ?>/" + obj.link + "'></div>";
        tmpl += "<div class='fleft  isactive_" + obj.active + " '>" + obj.note + "</div>";
        return tmpl;
    },
    list_url: '/admin/sections/',
    list_on:  {
        "onKeyPress": function (key) {
            SectPage.keyPressAction(this, key);
        },
        "onAfterLoad": function () {
            this.config.height = (window.innerHeight - 170);
            this.resize();
        }
    }
});

var Roles_Page = new MView({
    id: "roles",
    list_css: "roles",
    list_template: function(obj){
        var tmpl = "<div class='fleft roles isactive_" + obj.active + "'>" + obj.name + "</div>";
            tmpl += "<div class='fleft isactive_" + obj.active + "'>" + obj.note + "</div>";
        return tmpl;
    },
    list_on: {
        "onKeyPress": function (key) {
            Roles_Page.keyPressAction(this, key);
        },
        "onAfterSelect": function () {
            item = $$('list_roles').getSelectedItem();
            // Закрываем все открытые формы редактирования
            $$('list_roles_rights').getParentView().back();
            $$('list_roles_rights').clearAll();

            $$('list_roles_rights').load("/roles/select/?id=" + item.id);
        },
    },
    list_url: "/roles/showTable/"
});

var Rights_Page = new MView({
    id: "roles_rights",
    savefunct: "saveRights",
    hideAddButton: true,
    hideDelButton: true,
    list_template: "<div class='fleft permission permission_#slname#' title='#slname#'></div><div class='fleft'>#sectname#</div>",
});

var Auth_Page = new MView({
    id: "auth",
    list_template: "<div class='fleft username isactive_#active#' title='#login#'>#note#</div><div class='fleft' title='Роль'>#name#</div>",
    list_url: '/auth/showTable/',
    list_on: {
        "onKeyPress": function (key) {
            Auth_Page.keyPressAction(this, key);
        },
    }
});

var Nets_Page = new MView({
    id           : "nets",
    list_template: "<div class='fleft webix_icon fa-share'>#note#</div><div class='fleft username isactive_#active#'>#nets#</div><div class='fleft' title='Роль'>#note#</div>",
    list_url: '/auth/getNets/',
    list_on: {
        "onKeyPress": function (key) {
            Nets_Page.keyPressAction(this, key);
        },
    }
});

<?php endif; ?>

/******************************************** For ALL ***********************************************/

maintable = {
    rows:[
        {
           view:"tabbar", css: "itbase_tabs" , multiview:true, options: [
                { value: "<span class='webix_icon fa-cogs'></span><span style='padding-left: 8px'>Роли / Права</span>", width:250, id:'p1' },
                { value: "<span class='webix_icon fa-male'></span><span style='padding-left: 8px'>Пользователи системы</span>", width:250, id: 'p2' },
                { value: "<span class='webix_icon fa-list-ul'></span><span style='padding-left: 8px'>Разделы</span>", width:250, id: 'p3' },
                { value: "<span class='webix_icon fa-sitemap'></span><span style='padding-left: 8px'>Сети</span>", width:250, id: 'p4' },
            ]
        },
        {
            animate: false,
            cells:[
                   {
                      id: "p1",
                      cols:[
                            { rows:[Roles_Page] },
                            { rows: [ Rights_Page ] },
                      ]
                   },
                   { 
                      id:"p2",
                      rows: [ Auth_Page ],
                    },
                   {
                      id :"p3",
                      rows: [ SectPage ]
                   }, 
                   {
                      id :"p4",
                      rows: [ Nets_Page ]
                   },        
            ] 
        } 
    ]
};
