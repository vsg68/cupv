var usrToolBar = new ToolBar("Пользователи", "users");
usrToolBar.cols.push({ id: 'filter_mbox', view: 'text', placeholder: 'Filter..', width: 200}, {});
var aliasToolBar = new ToolBar("Алиас", "aliases");
var fwdToolBar = new ToolBar("Форвард", "fwd");
var groupToolBar = new ToolBar("Группы", "groups");

//  Вывод пользователей
var lusers = {
    id: 'list_users',
    view: "list",
    template: function (obj) {
        var tmpl = "<div class='fleft mailbox " + (obj.active == "0" ? "inactive" : "") + "'>" + obj.mailbox + "</div>";
        tmpl += "<div class='fleft username' title='username'>" + obj.username + "</div>";
        if (obj.imap_enable == "1")
            tmpl += "<div class='fleft fa-envelope webix_icon' title='imap_enable'></div>";
        else
            tmpl += "<div class='fleft fa-envelope-o webix_icon' title='imap_disable'></div>";
        if (obj.master_admin == "1")  tmpl += "<div class='fleft fa-male webix_icon' title='master_admin'></div>";
        if (obj.master_domain == "1") tmpl += "<div class='fleft fa-users webix_icon' title='master_domain'></div>";
        if (obj.allow_nets) {
            net = obj.allow_nets.split(';');
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
        if (obj.last_login)    tmpl += "<div class='fleft last_login' title='last_login'>" + obj.last_login + "</div>";

        return tmpl;
    },
    type: { height: 40 },
    select: true,
    css: "ftab",
    on: {
        "onAfterSelect": function () {
            item = $$('list_users').getSelectedItem();
            $$('list_aliases').clearAll();
            $$('list_aliases').load("/users/select/?q=alias&mbox=" + item.mailbox);
            $$('list_fwd').clearAll();
            $$('list_fwd').load("/users/select/?q=fwd&mbox=" + item.mailbox);
            $$('list_groups').clearAll();
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
        defaults = {"active":1, "is_new":1};
        if (abr == "users") {
            defaults["allow_nets"] = "192.168.0.0/24";
        }
        // Если это мультиформа для групп - то нас будет интересовать user_id для передачи на сервер
        // Если она пользовательская, то пропускаем дальнейшее
        else {
            selected_User = $$("list_users").getSelectedItem()
            defaults[ $$("list_" + abr).config.linkfield ] = (abr == "groups") ? selected_User.id : selected_User.mailbox;
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

        id = $$("list_" + abr).getSelectedId();
        webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
            //  тут надо отослать данные на сервер
            if (result) {
                webix.ajax().post("/" + abr + "/post.php", {id: id, type: "del"}, function (text) {
                    if (text)
                        $$("list_" + abr).remove();
                    else
                        webix.message({type: "error", text: "Что-то пошло не так"});
                })
            }
        }})
    }
};

/*********   Users  ********/
usrToolBar.cols.push(buttonPlus, buttonMinus);
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
aliasToolBar.cols.push({}, webix.copy(buttonPlus), webix.copy(buttonMinus));
var aliasForm = new mViewAdm("aliases_mv");

/*********   Forward  ********/
fwdToolBar.cols.push({}, webix.copy(buttonPlus), webix.copy(buttonMinus));
var fwdForm = new mViewAdm("fwd_mv");
fwdForm.cells[1].elements[2].hidden = false;
fwdForm.cells[1].elements[1].hidden = true;

/*********   Groups  ********/
groupToolBar.cols.push({}, webix.copy(buttonPlus), webix.copy(buttonMinus));
var groupForm = new mViewAdm("groups_mv");
groupForm.cells[1].elements[1] = {view: "richselect", label: "Группа", name: "name", options: "/groups/select/" };
groupForm.cells[1].elements.splice(2, 2);
groupForm.cells[1].rules = {
                        name: function (value) {
                                checkGroups(value);
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
var groupForm = new mView("group_mv");
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
        {rows: [ usrToolBar, usersForm ]},
        {rows: [ aliasToolBar, aliasForm ]},
        {rows: [ fwdToolBar, fwdForm ]},
        {rows: [ groupToolBar, groupForm ]},
    ]
};
