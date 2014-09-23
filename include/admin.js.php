
<?php if( $permissions == $WRITE_LEVEL ): ?>

/*********   USER PAGE  ********/

var SectPage = new PageAdm({
    id: "admin_sect",
    toolbarlabel: "Разделы",
    list_template: function (obj) {
        var tmpl = "<div class='fleft syslogtag isactive_" + obj.active + "'>" + obj.name + "</div>";

        tmpl += "<div class='fleft fa-link webix_icon' title='http://<?= $_SERVER["SERVER_NAME"] ?>/" + obj.link + "'></div>";
        tmpl += "<div class='fleft  isactive_" + obj.active + " '>" + obj.note + "</div>";
        return tmpl;
    },
    formElements: [
        {view: "text", label: "Название", name: "name"},
        {view: "text", label: "Описание", name: "note"},
        {view: "richselect", label: "http", name: "link", options: "/admin/get_ctrl" },
        {view: "checkbox", label: "active", name: "active"},
        webix.copy(save_cancel_button),
        {}
    ],
    formRules: {
        name: webix.rules.isNotEmpty,
        link: webix.rules.isNotEmpty
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

var Roles_Page = new PageAdm({
    id: "roles",
    toolbarlabel: "Роли",
    list_css: "roles",
    list_template: function(obj){
        var tmpl = "<div class='fleft roles isactive_" + obj.active + "'>" + obj.name + "</div>";
            tmpl += "<div class='fleft isactive_" + obj.active + "'>" + obj.note + "</div>";
        return tmpl;
    },
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

var Rights_Page = new PageAdm({
    id: "roles_rights",
    toolbarlabel: "Права",
    savefunct: "saveRights",
    hideAddButton: true,
    hideDelButton: true,
    list_template: "<div class='fleft permission permission_#slname#' title='#slname#'></div><div class='fleft'>#sectname#</div>",
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
        {}
    ],
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

var Auth_Page = new PageAdm({
    id: "auth",
    toolbarlabel: "Пользователи",
    list_template: "<div class='fleft username isactive_#active#' title='#login#'>#note#</div><div class='fleft' title='Роль'>#name#</div>",
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
        {}
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
    list_url: '/auth/showTable/',
    list_on: {
        "onKeyPress": function (key) {
            Auth_Page.keyPressAction(this, key);
        },
    }
});

<?php else: ?>
/*********   USER PAGE  ********/

<?php endif; ?>

/******************************************** For ALL ***********************************************/

maintable = {
    view: "accordion",
    css:"accord",
    multi: "mixed",
    cols:[
       {
          header:"Роли / Права",
          body:{
              cols:[
                    { rows:[Roles_Page], gravity:3 },
                    { view:"resizer"},
                    { rows: [ Rights_Page ], gravity:2 },
              ]
          },
          gravity:2
       },
       { 
          header :"Пользователи системы",
          body: {
              rows: [ Auth_Page ]
          },
        },
       {
          header :"Разделы",
          collapsed: true,
          body: {
              rows: [ SectPage ]
          },
       },        
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
