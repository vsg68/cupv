function ToolBar(label, abr) {
    this.view = "toolbar";
    this.height = 30;
    this.abr = abr;
    this.cols = [
        {view: "label", label: label}
    ]
}

function mViewAdm(id) {
    this.view = "multiview";
    this.abbreviate = id.split("_")[0];
    this.newID;
    this.fitBiggest = true;
    this.minWidth = 250;
    this.maxWidth = 300;
    this.cells = [
        { id: "list_" + this.abbreviate, view: "list",
            linkfield: "", // поле привязки к пользовательскому ящику
            scroll: false, select: true,
            template: "<div class='isactive_#active#'>#alias_name#</div>",
            on: { "onKeyPress": function (key) {
                keyPressAction(this, key);
            }
            }},
        { id: "form_" + this.abbreviate, view: "form", elementsConfig: {labelWidth: 70}, elements: [
            {view: "text", label: "id", name: "id", hidden: true },
            {view: "text", label: "alias", name: "alias_name" },
            {view: "text", label: "forward", name: "delivery_to", hidden: true },
            {view: "checkbox", label: "active", name: "active"},
            { margin: 5, cols: [
                {},
                { view: "button", value: "Cancel", width: 70, click: "cancel()" },
                { view: "button", value: "Save", width: 70, type: "form", click: "save_form" },
                {}
            ]},
            {}
        ],
            rules: {
                alias_name: webix.rules.isEmail,
                delivery_to: webix.rules.isEmail
            }
        }
    ]
}

function mView(id) {
    this.maxWidth = 300;
    this.abbreviate = id.split("_")[0];
    this.cells = [
        { id: "list_" + this.abbreviate, view: "list",
            linkfield: "", // поле привязки к пользовательскому ящику
            scroll: false, select: true,
            template: "<div class='isactive_#active#'>#alias_name#</div>",
            on: { "onKeyPress": function (key) {
                keyPressAction(this, key);
            }}
        }
    ]
}

// Реакция на клавиши списка пользователей
function keyPressAction(list, key) {

    multiview = list.getParentView();
    if (multiview.config.view == "multiview") {

        children = multiview.getChildViews();

        for (i = 0; i < children.length; i++) {
            if (children[i].config.view == "form")
                form = children[i];
        }
    }

    currID = list.getSelectedId();
    Ind = -1;
    if (key == 40) {   // down arrow
        Ind = list.getIndexById(list.getNextId(currID));
    }
    else if (key == 38) {  // up arrow
        Ind = list.getIndexById(list.getPrevId(currID));
    }
    else if (key == 113 && form && currID) { // edit
        form.show();
    }
    else if (key == 27 && multiview) { //cancel edit
        multiview.back();
    }
    if (Ind >= 0) {
        list.select(list.getIdByIndex(Ind));
    }
}
// Сохранение формы
function save_form(){
    //    валидация!! {}
    mForm = this.getFormView();
    abrv = (mForm.config.id.split("_"))[1];

    if ( mForm.save() === false)  return false;
    // Исключение для форварда
    abrv = (abrv == "fwd" ) ? "aliases" : abrv;

    webix.ajax().post("/" + abrv + "/save", mForm.getValues(),
        function(responce){
            if(responce)
                webix.message({type:"error", expire: 3000, text: responce}); // server side response
            else {
                webix.message("ОK"); // server side response
                var mView = mForm.getParentView();
                mView.config.newID = "";
                mView.back();
                // TODO после удачного добавления нужно удалить поле is_new
            }
        })
}

// Отмена изменений
function cancel() {
    var mView = this.getFormView().getParentView();
    var newID = mView.config.newID;
    if (newID) {
        $$("list_" + mView.config.abbreviate).remove(newID);
    }
    mView.back();
}

// Проверка на существования адреса и id, а так же правильность домена
function checkEmail(value) {
    var valid = false;
    var mForm = $$('form_users').getValues();
    if (webix.rules.isEmail(value)) {
        webix.ajax().sync().get("/users/validateEmail/", { mbox: mForm.mailbox, id: mForm.id }, function (responce) {
            valid = responce;  // responce
        });
        if (!valid)
            webix.message({type: "error", expire: 3000, text: "Проверьте адрес и домен"});
    }
    return valid;
}

// проверка наличия одинаковых групп у пользователя
function checkGroups(value){
    lastid = $$('list_groups').getLastId();
    currid = $$('list_groups').getFirstId();
    while (1) {
        item = $$('list_groups').getItem(currid);
        if (item.name == value) {
            webix.message({ type: "error", text: "Пользователь в данной группе уже присутствует" });
            return false;
        }
        if (currid == lastid)
            return true;
        currid = $$('list_groups').getNextId(currid);
    }
}

// Проверка открытой ячейки в мультивью
function isActiveCell_List(abr) {
    multiview = $$("list_" + abr).getParentView(); // multiview

    if (multiview.config.view != "multiview")
        return false;

    activeID = multiview.getActiveId();

    return activeID == "list_" + abr;
}

// Функция проверки правильности значения поля формы
function fnTestByType(type, str) {

    one_net = "(\\d{1,3}\\.){3}\\d{1,3}(/\\d{1,2})?";
    net_tmpl = "^\\s*" + one_net + "(\\s*,\\s*" + one_net + ")*\\s*$";
    mail_tmpl = "^[-_\\w\\.]+@(\\w+\\.){1,}\\w+$";
    word_tmpl = "^[\\w\\.]+$";
    transp_tmpl = "^\\w+:\\[(\\d{1,3}\\.){3}\\d{1,3}\\]$";
    domain_tmpl = "^(\\w+\\.)+\\w+$";
    date_tmpl = "^\\d{4}-\\d{2}-\\d{2}$";
    int_tmpl = "^\\d+$";

    switch (type) {
        case 'mail':
            reg = new RegExp(mail_tmpl, 'i');
            break;
        case 'nets':
            reg = new RegExp(net_tmpl, 'i');
            break;
        case 'domain':
            reg = new RegExp(domain_tmpl, 'i');
            break;
        case 'transport':
            reg = new RegExp(transp_tmpl, 'i');
            break;
        case 'int':
            reg = new RegExp(int_tmpl, 'i');
            break
        case 'date':
            reg = new RegExp(date_tmpl, 'i');
            break;
        default:
            return false;
    }

    return (reg.test(str));

}
