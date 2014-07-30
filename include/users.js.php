var usrToolBar = new ToolBar("Пользователи", "users");
usrToolBar.cols.push({ id: 'filter_mbox', view: 'text', css:"filter", placeholder: 'Filter..', width: 200});
var aliasToolBar = new ToolBar("Псевдонимы", "aliases");
var fwdToolBar = new ToolBar("Пересылка", "fwd");
var groupToolBar = new ToolBar("Списки рассылки", "groups");

//  Вывод пользователей
var lusers = {
    id: 'list_users',
    view: "list",
    template: function (obj) {
        x = obj;
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
    type: { height: 40 },
    select: true,
    css: "ftab",
    on: {
        "onAfterSelect": function () {
            item = $$('list_users').getSelectedItem();
            // Закрываем все открытые формы редактирования
            $$('list_aliases').getParentView().back(); $$('form_fwd').getParentView().back(); $$('form_groups').getParentView().back();
            $$('list_aliases').clearAll(); $$('list_fwd').clearAll(); $$('list_groups').clearAll();

            $$('list_aliases').load("/users/select/?q=alias&mbox=" + item.mailbox);
            $$('list_fwd').load("/users/select/?q=fwd&mbox=" + item.mailbox);
            $$('list_groups').load("/users/select/?q=group&user_id=" + item.id);
        },
        "onKeyPress": function (key) {
            keyPressAction(this, key);
        },
        "onAfterLoad": function () {
            this.config.height = (window.innerHeight - 140);
            if (window.innerWidth < 1500)  // 8-) minWidth
                this.config.width = 800;
            this.resize();
        }
    },
    url: "/users/showTable/"
};

<?php if( $permissions == $WRITE_LEVEL ): ?>
var buttonPlus = {
    view: "button", type: "iconButton", icon: "plus", label: "New", width: 70,
    click: function () {
        // Если не выбран пользователь - выходим
        abr = this.getParentView().config.abr;
        // Если кнопка нажата не на списке  - выходим
        if (!isActiveCell_List(abr)) {
            webix.message({ type: "error", text: "Кнопки в этой области не работают" });
            return false;
        }

        if (abr != "users" && $$("list_users").getSelectedId() == false) return 1;

        // заполняю дефолтными значениями
        // is_new - вспомогательное поле, которое проверяется на стороне сервера
        defaults      = {"active":1, "is_new":1};
        selected_User = $$("list_users").getSelectedItem();

        if (abr == "users") {
            defaults["allow_nets"] = "192.168.0.0/24";
        }
        // Если это мультиформа для групп - то нас будет интересовать user_id для передачи на сервер
        // Если она пользовательская, то пропускаем дальнейшее
        else if (abr == "groups") {
            defaults[ $$("list_" + abr).config.linkfield ] =  selected_User.id;
        }
        else {
            defaults[ $$("list_" + abr).config.linkfield ] = selected_User.mailbox;
        }

        if (abr == "aliases"){
            defaults["alias_name"] = selected_User.mailbox;
        }
        else if ( abr == "fwd") {
            defaults["delivery_to"] = selected_User.mailbox;
        }

        newID = $$("list_" + abr).add(defaults);     // создаем новую запись
        // заносим новый ид в переменную.
        $$("list_" + abr).getParentView().config.newID = newID;

        // Переход к редактированию
        $$("form_" + abr).show();
        $$("list_" + abr).select(newID);
    }
};
var buttonMinus = {
    view: "button", type: "iconButton", icon: "minus", label: "Del", width: 70,
    click: function () {

        abr = this.getParentView().config.abr;
        // Если кнопка нажата не на списке - выходим
        if (!isActiveCell_List(abr)) {
            webix.message({ type: "error", text: "Кнопки в этой области не работают" });
            return false;
        }

        var selected_id = $$("list_" + abr).getSelectedId();
        webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
            //  тут надо отослать данные на сервер
            if (result) {
                webix.ajax().post("/" + abr + "/delEntry/", {id: selected_id}, function (text, xml, xhr) {
                    if (!text)
                        $$("list_" + abr).remove(selected_id);
                    else
                        webix.message({type: "error", text: text});
                })
            }
        }})
    }
};

/*********   Users  ********/
usrToolBar.cols.push(buttonPlus);
//  Форма редактирования пользователя
var dform = {
    id: "form_users",
    view: "form",
    elementsConfig: {labelWidth: 150},
    elements: [
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
        { margin: 15, cols: [
            {},
            { view: "button", value: "Cancel", width: 100, click: "cancel()" },
            { view: "button", value: "Save", width: 100, type: "form", click: "save_form()" },
            {}

        ]},
        {}  // spacer
    ],
    rules: {
        mailbox: function (value) {
            return  checkEmail(value);
        },
        allow_nets: function (value) {
            return fnTestByType("nets", value);
        },
        username: webix.rules.isNotEmpty,
        password: webix.rules.isNotEmpty
    }
};
//  Форма для вывода пользователей и формы редактирования
var usersForm = {
    view: "multiview",
    newID: "",
    abbreviate: "users",
    cells: [ lusers, dform ] };

/*********   Aliases  ********/
// добавляем "свободное место" и кнопки на тулбар
aliasToolBar.cols.push({}, webix.copy(buttonPlus), webix.copy(buttonMinus));
var aliasForm = new mViewAdm("aliases_mv");
aliasForm.cells[1].elements.splice(1,1);  // pop  "delivery_to"

/*********   Forward  ********/
fwdToolBar.cols.push({}, webix.copy(buttonPlus), webix.copy(buttonMinus));
var fwdForm = new mViewAdm("fwd_mv");
//fwdForm.cells[1].elements[0] = {view: "text", label: "forward", name: "delivery_to"},
fwdForm.cells[1].elements.splice(0,1);  // pop  "alias_name"

/*********   Groups  ********/
groupToolBar.cols.push({}, webix.copy(buttonPlus), webix.copy(buttonMinus));
var groupForm = new mViewAdm("groups_mv");
groupForm.cells[1].elements[0] = {view: "richselect", label: "Группа", name: "name",
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
};
// group_id и user_id передаются как скрытые поля. для их "получения" нужно применять form.getValues()
groupForm.cells[1].elements.splice(1,1);
groupForm.cells[1].rules = {
                        name: function (value) {
                                return checkGroups(value);
                        }
};
<?php else: ?>
/*********   Users  ********/
var usersForm = {
    minWidth: 800,
    abbreviate: "users",
    cells: [ lusers ] };

/*********   Aliases  ********/
var aliasForm = new mView("aliases_mv");

/*********   Forward  ********/
var fwdForm = new mView("fwd_mv");

/*********   Groups  ********/
var groupForm = new mView("groups_mv");
<?php endif; ?>

/******************************************** For ALL ***********************************************/

aliasForm.cells[0].linkfield = "delivery_to";
// set defaults

fwdForm.cells[0].template = "<div class='isactive_#active#'>#delivery_to#</div>";
fwdForm.cells[0].linkfield = "alias_name";

groupForm.cells[0].template = "<div class='isactive_#active#'>#name#</div>";
groupForm.cells[0].linkfield = "user_id";

maintable = {
    cols: [
        {rows: [ usrToolBar, usersForm ], minWidth: 800, gravity:5},
        {rows:[
            {rows: [ aliasToolBar, aliasForm ]},
            {rows: [ fwdToolBar, fwdForm ]},
            {rows: [ groupToolBar, groupForm ]}
            ], minWidth: 300, gravity:3},
    ]
};


//TODO
// 1) при создании пользователя - по окончании, переход на него
// 2) заполнение строки "пароль" - по клику на иконку
// 3) заполниние строки "сети" -  по клику на иконку (?)
// 4) экспорт в файл ....
// 5) При клике на почтовую иконку - переход на почту(www) в транскрипции user@domain*i_am@gmpro.ru
