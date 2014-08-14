// Функция наследования на классах
function extend(Child, Parent) {
    var F = function() { }
    F.prototype = Parent.prototype
    Child.prototype = new F()
    Child.prototype.constructor = Child
    Child.superclass = Parent.prototype
}

function ToolBar(label, abr) {
    this.view = "toolbar";
    this.height = 30;
    this.abr = abr;
    this.cols = [
        {view: "label", label: label}
    ]
}

var save_cancel_button = {
    margin: 5,
    cols: [
        {},
        { view: "button", value: "Cancel", width: 70, click: function(){ this.getFormView().config.cancel()} },
        { view: "button", value: "Save", width: 70, type: "form", click: function(){ this.getFormView().config.save_form()} },
        {}
    ]
};

function mViewAdm(id) {
    this.id = id;
    this.view = "multiview";
    this.objID = id.split("_")[0];
    this.newID;
//    this.fitBiggest = true;
    this.cells = [
        { id: "list_" + this.objID, view: "list",
            linkfield: "", // поле привязки к пользовательскому ящику
            scroll: false, select: true,
            template: "<div class='isactive_#active#'>#alias_name#</div>",
            on: { "onKeyPress": function (key) {
                keyPressAction(this, key);
            }
            }},
        { id: "form_" + this.objID, view: "form", elementsConfig: {labelWidth: 110}, elements: [
                {view: "text", label: "Псевдоним", name: "alias_name" },
                {view: "text", label: "Пересылка", name: "delivery_to" },
                {view: "checkbox", label: "Активно", name: "active"},
                webix.copy(save_cancel_button),
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
    this.objID = id.split("_")[0];
    this.cells = [
        { id: "list_" + this.objID, view: "list",
            linkfield: "", // поле привязки к пользовательскому ящику
            scroll: false, select: true,
            template: "<div class='isactive_#active#'>#alias_name#</div>",
            on: { "onKeyPress": function (key) {
                keyPressAction(this, key);
            }}
        }
    ]
}


function getObjID(id){
    chunks = id.split("_");
    return (chunks[1] + "_" + chunks[2]);
}

function save_form_group(){

    var mForm = this.getFormView();
    objID = getObjID(mForm.config.id);
    hreflink = (objID.split("_"))[0];

    // Если не новая запись - убираем признак новой записи
    if( $$('list_'+ objID).getItem( mForm.getValues().id ).value )
        mForm.setValues({is_new:0},true);

    if ( mForm.save() === false)  return false;

    webix.ajax().post("/" + hreflink + "/savegroup", mForm.getValues(),
        function(responce){
            if(responce)
                webix.message({type:"error", expire: 3000, text: responce}); // server side response
            else {
                webix.message("ОK"); // server side response
                var mView = mForm.getParentView();
                mView.config.newID = "";
                $$('list_' + objID).openAll();
                mView.back();
            }
        })
}
// Проверка на существования адреса и id, а так же правильность домена
function checkEmail(value) {
    var valid = false;
    var mForm = $$('form_users_first').getValues();
    if (webix.rules.isEmail(value)) {
        webix.ajax().sync().get("/users/validateEmail/", { mbox: mForm.mailbox, id: mForm.id }, function (responce) {
            valid = responce;  // responce
        });
        if (!valid)
            webix.message({type: "error", expire: 3000, text: "Проверьте адрес и домен"});
    }
    return valid;
}

// проверка наличия основного домена у домена - псевдонима
function chkDomainAlias(value) {
    var ok = false;
    $$('list_domains_second').data.each( function(obj){
        if (obj.domain_name == value && obj.domain_type == "0" ) {
            ok = true;
        }
    });
    if (! ok)
        webix.message({ type: "error", text: "Основных доменов с таким названием не существует" });

    return ok;
}

function checkGroups(value) {
    var ok = true;
    $$('list_groups_first').data.each( function(obj){
        if (obj.name == value) {
            webix.message({ type: "error", text: "Пользователь в данной группе уже присутствует" });
            ok = false;
        }
    });
    return ok;
}

// проверка наличия одинаковых пользователей в группе
function chkUserInGroup(pid, name){
    var ok = true;
    var msg = pid ? "Пользователь в данной группе уже присутствует" : "Такая группа уже существует";

    $$('list_groups_second').data.eachChild(pid, function(obj){

        if (obj.value == name) {
            webix.message({ type: "error", text: msg });
            ok = false;
        }
    });

    return ok;
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
    ip_tmpl = "(\\d{1,3}\\.){3}\\d{1,3}";

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
         case 'ip':
            reg = new RegExp(ip_tmpl, 'i');
            break;
        default:
            return false;
    }

    return (reg.test(str));

}

/*
 Входные параметры:
   id - кусок id для форм и списков. для упрощенной индентификации ОБЯЗАТЕЛЕН
   list_template - шаблон отображения списка
   bindfield - название поля-прявязки к записи пользователя.
   formElements - поля формы. Те, которые показываются
   formRules - правила проверки полей формы
   list_on  - перечень событий на которые реагирует list

 */

function MView(setup) {
    var objID = setup.id;  // Общее название
    var hreflink = objID.split("_")[0];  // Общее название
    this.isScroll = setup.isListScroll;
    this.toolbarlabel = setup.toolbarlabel || "";
    this.hideSearchField = ! setup.showSearchField;
    var filterRule = setup.filterRule || "";
    this.list_view = setup.list_view || "list";
    this.list_css  = setup.list_css || "ftab";
    this.list_url  = setup.list_url || "";
    this.list_scheme =  setup.list_scheme || {};
    this.list_template = setup.list_template || "<div class='isactive_#active#'>#name#</div>";  // Шаблон для отображения в list
    this.list_on = setup.list_on || {};

    this.keyPressAction = function(list, key ,formId){

        var multiview = list.getParentView();
        var form;

        if (multiview.config.view == "multiview") {

            var children = multiview.getChildViews();

            if ( formId != undefined ) {
                form = $$(formId);      // Если указывается ID формы
            }
            else {
                for (i = 0; i < children.length; i++) {

                    if (children[i].config.view == "form") {
                        form = children[i];
                        break;
                    }
                }
            }
        }

        var currID = list.getSelectedId();
        var Ind = -1;
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
    };

    this.rows = [
       {
            view: "toolbar",
            height: 30,
            cols: [
                {
                    view: "label",
                    label: this.toolbarlabel
                },
                {
                    view: 'text',
                    css:"filter",
                    placeholder: 'Filter..',
                    width: 200,
                    hidden: this.hideSearchField,
                    on: {
                        "onTimedKeyPress": function() {
                            var value = this.getValue().toLowerCase();
                            $$('list_' + objID).filter( function(obj) {
                                return filterRule(obj, value);
                            } );
                        }
                    }
                },

            ]
        },
       {
            view: "multiview",
            cells: [
                {
                    id: "list_" + objID,
                    view: this.list_view,
                    scheme: this.list_scheme,
                    scroll: this.isScroll,
                    css: this.list_css,
                    type: { height: 40 },
                    select: true,
                    template: this.list_template,
                    url: this.list_url,
                    on: this.list_on
                }
            ]
        }
    ]
};

function MAdmView(setup) {

    extend(MAdmView, MView);    // Наследуем
    MView.apply(this, arguments);  // Запускаем родительский конструктор

    var objID           = setup.id;  // Общее название
    this.ID             = setup.id;  // Для доступа снаружи
    var hreflink        = setup.id.split("_")[0];  // Общее название
    this.hideAddButton  = setup.hideAddButton;
    this.hideDelButton  = setup.hideDelButton;
    this.formID         = setup.formID || "form_" + objID;
    this.formElements   = setup.formElements || [
                                        {view: "text", label: "Псевдоним", name: "alias_name" },
                                        {view: "text", label: "Пересылка", name: "delivery_to" },
                                        {view: "checkbox", label: "Активно", name: "active"},
                                        webix.copy(save_cancel_button),
                                        {}];
    this.formRules      = setup.formRules || {};

    var __addButtonClick = setup.addButtonClick || function(){return {}};
    this.list_bind      = function(id){
        id = ( id == undefined) ? this.formID : id;
        $$(id).bind('list_' + objID);
    };


    function isActiveCell_List() {

        var multiview = $$("list_" + objID).getParentView(); // multiview

        if (multiview.config.view != "multiview")
            return false;

        activeID = multiview.getActiveId();

        return activeID == "list_" + objID;
    };

    this.rows[0].cols.push(
        {
            view: "button",
            type: "iconButton",
            icon: "plus",
            label: "New",
            width: 70,
            hidden: this.hideAddButton,
            click:  function(){
                // Если кнопка нажата не на списке  - выходим
                if (!isActiveCell_List()) {
                    webix.message({ type: "error", text: "Кнопки в этой области не работают" });
                    return false;
                }
                defaults = __addButtonClick();

                if( defaults == false )   return false;

                defaults["is_new"] = 1;
                defaults["active"] = 1;
                // Переход к редактированию
                $$("form_" + objID).show();
                // создаем новую запись
                $$("list_" + objID).select( $$("list_" + objID).add(defaults) );
            }
        },
        {
            view: "button",
            type: "iconButton",
            icon: "trash-o",
            label: "Del",
            width: 70,
            hidden: this.hideDelButton,
            click: function(){

                // Если кнопка нажата не на списке - выходим
                if (! isActiveCell_List()) {
                    webix.message({ type: "error", text: "Кнопки в этой области не работают" });
                    return false;
                }

                var selected_id = $$("list_" + objID).getSelectedId();

                webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
                    //  тут надо отослать данные на сервер
                    if (result) {
                        webix.ajax().post("/" + (hreflink == "fwd" ? "aliases" : hreflink ) + "/delEntry/", {id: selected_id}, function (text, xml, xhr) {
                            if (!text)
                                $$("list_" + objID).remove(selected_id);
                            else
                                webix.message({type: "error", text: text});
                        })
                    }
                }})
            }
        }
    );

    this.rows[1].cells.push({
        id: this.formID,
        view: "form",
        elementsConfig: {labelWidth: 110},
        elements: this.formElements,
        rules: this.formRules,
        save_form: function(){
            var mForm = $$(this.id);

            var values =  mForm.getValues();

            if(values.is_new == undefined)
                values.is_new = 0;
            // Если не новая запись - убираем признак новой записи
            mForm.setValues({is_new:0},true);

            if ( mForm.save() === false)  return false;
            // Исключение для форварда
            hreflink = (hreflink == "fwd" ) ? "aliases" : hreflink;

            webix.ajax().post("/" + hreflink + "/save", values,
                function(responce){
                    if(responce)
                        webix.message({type:"error", expire: 3000, text: responce}); // server side response
                    else {
                        webix.message("ОK"); // server side response
                        mForm.getParentView().back();
                    }
                }
            );
        },
        cancel: function () {
            mView = $$(this.id).getParentView();
            values = $$(this.id).getValues();
            if (values.is_new) {
                $$("list_" + objID).remove( values.id );
            }
            mView.back();
    }

});


};
//function MAdmView(setup) {
//
//    extend(MAdmView, MView);    // Наследуем
//    MView.apply(this, arguments);  // Запускаем родительский конструктор
//
//    var objID           = setup.id;  // Общее название
//    this.ID             = setup.id;  // Для доступа снаружи
//    var hreflink        = setup.id.split("_")[0];  // Общее название
//    this.hideAddButton  = setup.hideAddButton;
//    this.hideDelButton  = setup.hideDelButton;
//    this.formID         = setup.formID || "form_" + objID;
//    this.formElements   = setup.formElements || [
//                                        {view: "text", label: "Псевдоним", name: "alias_name" },
//                                        {view: "text", label: "Пересылка", name: "delivery_to" },
//                                        {view: "checkbox", label: "Активно", name: "active"},
//                                        webix.copy(save_cancel_button),
//                                        {}];
//    this.formRules      = setup.formRules || {};
//
//    var __addButtonClick = setup.addButtonClick || function(){return {}};
//    this.list_bind      = function(id){
//        id = ( id == undefined) ? this.formID : id;
//        $$(id).bind('list_' + objID);
//    };
//
//
//    function isActiveCell_List() {
//
//        var multiview = $$("list_" + objID).getParentView(); // multiview
//
//        if (multiview.config.view != "multiview")
//            return false;
//
//        activeID = multiview.getActiveId();
//
//        return activeID == "list_" + objID;
//    };
//
//    this.rows[0].cols.push(
//        {
//            view: "button",
//            type: "iconButton",
//            icon: "plus",
//            label: "New",
//            width: 70,
//            hidden: this.hideAddButton,
//            click:  function(){
//                // Если кнопка нажата не на списке  - выходим
//                if (!isActiveCell_List()) {
//                    webix.message({ type: "error", text: "Кнопки в этой области не работают" });
//                    return false;
//                }
//                defaults = __addButtonClick();
//
//                if( defaults == false )   return false;
//
//                defaults["is_new"] = 1;
//                defaults["active"] = 1;
//                // Переход к редактированию
//                $$("form_" + objID).show();
//                // создаем новую запись
//                $$("list_" + objID).select( $$("list_" + objID).add(defaults) );
//            }
//        },
//        {
//            view: "button",
//            type: "iconButton",
//            icon: "trash-o",
//            label: "Del",
//            width: 70,
//            hidden: this.hideDelButton,
//            click: function(){
//
//                // Если кнопка нажата не на списке - выходим
//                if (! isActiveCell_List()) {
//                    webix.message({ type: "error", text: "Кнопки в этой области не работают" });
//                    return false;
//                }
//
//                var selected_id = $$("list_" + objID).getSelectedId();
//
//                webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
//                    //  тут надо отослать данные на сервер
//                    if (result) {
//                        webix.ajax().post("/" + (hreflink == "fwd" ? "aliases" : hreflink ) + "/delEntry/", {id: selected_id}, function (text, xml, xhr) {
//                            if (!text)
//                                $$("list_" + objID).remove(selected_id);
//                            else
//                                webix.message({type: "error", text: text});
//                        })
//                    }
//                }})
//            }
//        }
//    );
//
//    this.rows[1].cells.push({
//        id: this.formID,
//        view: "form",
//        elementsConfig: {labelWidth: 110},
//        elements: this.formElements,
//        rules: this.formRules,
//        save_form: function(){
//            var mForm = $$(this.id);
//
//            var values =  mForm.getValues();
//
//            if(values.is_new == undefined)
//                values.is_new = 0;
//            // Если не новая запись - убираем признак новой записи
//            mForm.setValues({is_new:0},true);
//
//            if ( mForm.save() === false)  return false;
//            // Исключение для форварда
//            hreflink = (hreflink == "fwd" ) ? "aliases" : hreflink;
//
//            webix.ajax().post("/" + hreflink + "/save", values,
//                function(responce){
//                    if(responce)
//                        webix.message({type:"error", expire: 3000, text: responce}); // server side response
//                    else {
//                        webix.message("ОK"); // server side response
//                        mForm.getParentView().back();
//                    }
//                }
//            );
//        },
//        cancel: function () {
//            mView = $$(this.id).getParentView();
//            values = $$(this.id).getValues();
//            if (values.is_new) {
//                $$("list_" + objID).remove( values.id );
//            }
//            mView.back();
//    }
//
//});
//
//
//};