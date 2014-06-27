//  Вывод пользователей
var lusers = {
    id: 'list_user',
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
        if (obj.lastlog)    tmpl += "<div class='fleft last_login' title='last_login'>" + obj.lastlog + "</div>";

        return tmpl;
    },
    type: { height: 40 },
    select: true,
    css: "ftab",
    on: {
        "onAfterSelect": function () {
                item = $$('list_user').getSelectedItem();
                $$('list_aliases').clearAll();
                $$('list_aliases').load("/aliases/select/?q=alias&mbox=" + item.mailbox);
                $$('list_fwd').clearAll();
                $$('list_fwd').load("/aliases/select/?q=fwd&mbox=" + item.mailbox);
        },
        "onKeyPress": function (key) {
                keyPressAction(this, key);
        },
        "onAfterLoad": function(){
                this.config.height = (window.innerHeight - 140);
                if( window.innerWidth < 1500)  // 8-) minWidth
                    this.config.width = 800;
                this.resize();
        }
    },
    url: "/users/showTable/?q=mbox"
};

<?php if( $permissions == $WRITE_LEVEL ): ?>
/*********   Users  ********/
var usrToolBar = new ToolBarAdm("Пользователи","user");
//  Форма редактирования пользователя
var dform = {
    id: "form_user",
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
        {view: "text", label: "lastlog", name: "lastlog", disabled: true},
        {view: "text", label: "last_ip", name: "last_ip", disabled: true},
        {view: "text", label: "last_prog", name: "last_prog", disabled: true},
        {view: "checkbox", label: "active", name: "active"},
        { margin: 15, cols: [
            {},
            { view: "button", value: "Cancel", width: 100, click: "cancel()" },
            { view: "button", value: "Save", width: 100, type: "form", click: "save()" },
            {}

        ]},
        {}  // spacer
    ],
    rules:{
        mailbox: function(){ return checkEmail();},
        username:  webix.rules.isNotEmpty,
        password:  webix.rules.isNotEmpty
    }
};
//  Форма для вывода пользователей и формы редактирования
var usersForm = {
    view: "multiview",
    newID:"",
    abbreviate:"user",
    cells: [ lusers, dform ] };

/*********   Aliases  ********/
var aliasToolBar = new ToolBarAdm("Алиас","aliases");
var aliasForm = new mViewAdm("aliases_mv");

/*********   Forward  ********/
var fwdToolBar = new ToolBarAdm("Пересылка","fwd");
var fwdForm = new mViewAdm("fwd_mv");
    fwdForm.cells[1].elements[2].hidden = false;
    fwdForm.cells[1].elements[1].hidden = true;

/*********   Groups  ********/
var groupToolBar = new ToolBarAdm("Группы","group");
var groupForm = new mViewAdm("group_mv");

<?php else: ?>
/*********   Users  ********/
var usrToolBar = new ToolBar("Пользователи","user");
var usersForm = {
    minWidth: 800,
    abbreviate:"user",
    cells: [ lusers ] };

/*********   Aliases  ********/
var aliasToolBar = new ToolBar("Алиас","aliases");
var aliasForm = new mView("aliases_mv");

/*********   Forward  ********/
var fwdToolBar = new ToolBar("Форвард","fwd");
var fwdForm = new mView("fwd_mv");

/*********   Groups  ********/
var groupToolBar = new ToolBar("Группы","group");
var groupForm = new mView("group_mv");
<?php endif; ?>

/******************************************** For ALL ***********************************************/

usrToolBar.cols.unshift({ id: 'filter_mbox', view: 'text', placeholder: 'Filter..', width: 200});

aliasForm.cells[0].linkfield = "delivery_to";
// set defaults

fwdForm.cells[0].template = "<div class='isactive_#active#'>#delivery_to#</div>";
fwdForm.cells[0].linkfield = "alias_name";

maintable = {
    cols: [
        {rows: [ usrToolBar, usersForm ]},
        {rows: [ aliasToolBar, aliasForm ]},
        {rows: [ fwdToolBar, fwdForm ]},
        {rows: [ groupToolBar, groupForm ]},
    ]
};
