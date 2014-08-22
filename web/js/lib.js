// Функция наследования на классах
function extend(Child, Parent) {
    var F = function() { }
    F.prototype = Parent.prototype
    Child.prototype = new F()
    Child.prototype.constructor = Child
    Child.superclass = Parent.prototype
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

// Проверка на существования адреса и id, а так же правильность домена
function checkEmail(ID, value) {
    var valid = false;
    var mForm = $$(ID).getValues();
    if (webix.rules.isEmail(value)) {
        webix.ajax().sync().get("/users/validateEmail/", { mbox: mForm.mailbox, id: mForm.id }, function (response) {
            valid = response;  // response
        });
        if (!valid)
            webix.message({type: "error", expire: 3000, text: "Проверьте адрес и домен"});
    }
    return valid;
}

// проверка наличия основного домена у домена - псевдонима
function chkDomainAlias(ID, value) {
    var ok = false;
    $$("list_" + ID).data.each( function(obj){
        if (obj.domain_name == value && obj.domain_type == 0 ) {
            ok = true;
        }
    });
    if (! ok)
        webix.message({ type: "error", text: "Основных доменов с таким названием не существует" });

    return ok;
}

function checkGroups(ID, value) {
    var ok = true;
    $$("list_" + ID).data.each( function(obj){
        if (obj.name == value) {
            webix.message({ type: "error", text: "Пользователь в данной группе уже присутствует" });
            ok = false;
        }
    });
    return ok;
}

// проверка наличия одинаковых пользователей в группе
function chkUserInGroup(ID, pid, name){
    var ok = true;
    var msg = pid ? "Пользователь в данной группе уже присутствует" : "Такая группа уже существует";

    $$("list_" + ID).data.eachChild(pid, function(obj){

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

Date.prototype.toLocaleFormat = function(format) {
    var f = {y : this.getYear() + 1900,m : this.getMonth() + 1,d : this.getDate(),H : this.getHours(),M : this.getMinutes(),S : this.getSeconds()}
    for(var k in f)
        format = format.replace('%' + k, f[k] < 10 ? "0" + f[k] : f[k]);
    return format;
};

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
    var self = this;
    this.objID = setup.id;  // Общее название
    this.hreflink = setup.id.split("_")[0];  // Общее название
    this.isScroll = setup.isListScroll;
    this.toolbarlabel = setup.toolbarlabel || "";
    this.hideSearchField = ! setup.showSearchField;
    this.filterRule = setup.filterRule || "";
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
                    view: 'text',
                    css:"filter",
                    placeholder: 'Filter..',
                    width: 200,
                    hidden: this.hideSearchField,
                    on: {
                        "onTimedKeyPress": function() {
                            var value = this.getValue().toLowerCase();
                            $$('list_' + self.objID).filter( function(obj) {
                                return self.filterRule(obj, value);
                            } );
                        }
                    }
                },
                {
                    view: "label",
                    label: this.toolbarlabel
                },

            ]
        },
       {
            view: "multiview",
            cells: [
                {
                    id: "list_" + self.objID,
                    view: this.list_view,
                    scheme: this.list_scheme,
                    scroll: this.isScroll,
                    css: this.list_css,
                    type: { height: "auto" },
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
    var self            = this;
    extend(MAdmView, MView);    // Наследуем
    MView.apply(this, arguments);  // Запускаем родительский конструктор

    this.hideAddButton  = setup.hideAddButton;
    this.hideDelButton  = setup.hideDelButton;
    this.formID         = setup.formID || "form_" + this.objID;
    this.formElements   = setup.formElements || [
                                        {view: "text", label: "Псевдоним", name: "alias_name" },
                                        {view: "text", label: "Пересылка", name: "delivery_to" },
                                        {view: "checkbox", label: "Активно", name: "active"},
                                        webix.copy(save_cancel_button),
                                        {}];
    this.formRules      = setup.formRules || {};
    this.addButtonClick = setup.addButtonClick || function(){return {}};

    this.list_bind      = function(){
        for(i=0; i < self.rows[1].cells.length; i++) {
            if( self.rows[1].cells[i].view == "form" )
                $$(self.rows[1].cells[i].id).bind('list_' + self.objID);
        }
    };

    this.isActiveCell_List = function(ID) {
        var multiview = $$("list_" + ID).getParentView(); // multiview

        if (multiview.config.view != "multiview")
            return false;

        activeID = multiview.getActiveId();

        return activeID == "list_" + ID;
    };
 };

function PageAdm(setup) {
    var self = this;
    extend(PageAdm,MAdmView);    // Наследуем
    MAdmView.apply(this, arguments);  // Запускаем родительский конструктор

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
                if (! self.isActiveCell_List(self.objID)) {
                    webix.message({ type: "error", text: "Кнопки в этой области не работают" });
                    return false;
                }
                defaults = self.addButtonClick();

                if( defaults == false )   return false;

                defaults["is_new"] = 1;
                defaults["active"] = 1;
                // Переход к редактированию
                $$("form_" + self.objID).show();
                // создаем новую запись
                $$("list_" + self.objID).select( $$("list_" + self.objID).add(defaults) );
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
                if (! self.isActiveCell_List(self.objID)) {
                    webix.message({ type: "error", text: "Кнопки в этой области не работают" });
                    return false;
                }

                var selected_id = $$("list_" + self.objID).getSelectedId();

                webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
                    //  тут надо отослать данные на сервер
                    if (result) {
                        webix.ajax().post("/" + (self.hreflink == "fwd" ? "aliases" : self.hreflink ) + "/delEntry/", {id: selected_id}, function (text, xml, xhr) {
                            if (!text)
                                $$("list_" + self.objID).remove(selected_id);
                            else
                                webix.message({type: "error", text: text});
                        })
                    }
                }})
            }
        }
    );

    this.rows[1].cells.push(
        {
            id: this.formID,
            view: "form",
            elementsConfig: {labelWidth: 130},
            elements: this.formElements,
            rules: this.formRules,
            save_form: function(){
                var mForm = $$(this.id);

                var values =  mForm.getValues();

                if(values.is_new == undefined)
                    values.is_new = 0;

                if ( mForm.save() === false)  return false;

                // Если не новая запись - убираем признак новой записи
                mForm.setValues({is_new:0},true);

                // Исключение для форварда
                self.hreflink = (self.hreflink == "fwd" ) ? "aliases" : self.hreflink;

                webix.ajax().post("/" + self.hreflink + "/save", values,
                    function(response){
                        if(response)
                            webix.message({type:"error", expire: 3000, text: response}); // server side response
                        else {
                            webix.message("ОK"); // server side response
                            mForm.getParentView().back();
                            $$("list_" + self.objID).showItem(values.id);
                        }
                    }
                );
            },
            cancel: function() {
                mView = $$(this.id).getParentView();
                values = $$(this.id).getValues();
                if (values.is_new) {
                    $$("list_" + self.objID).remove( values.id );
                }
                mView.back();
            }
        }
    );
};

function PageTreeAdm(setup) {

    var self = this;
    extend(PageTreeAdm,MAdmView);    // Наследуем
    MAdmView.apply(this, arguments);  // Запускаем родительский конструктор

    this.formElements_rs = setup.formElements_rs;
    this.formRules_rs    = setup.formRules_rs;



    this.rows[0].cols.push(
        {
            view: "button",
            type: "iconButton",
            icon: "user",
            label: "New",
            width: 70,
            click: function () {
                var selected_item = $$("list_" + self.objID).getSelectedItem();

                // Если кнопка нажата не на списке  - выходим, если не выделено ничего - тоже выходим
                if (! self.isActiveCell_List(self.objID) || selected_item == undefined) {
                    webix.message({ type: "error", text: "Выделите группу, в которую будем добавлять пользователя" });
                    return false;
                }

                selected_id = selected_item.id;
                // если узел не является корнем, то ищем ID его корня
                if( $$('list_'+ self.objID).getParentId(selected_id) )
                    selected_id = $$('list_'+ self.objID).getParentId( selected_id );

                defaults = {"value":"", "is_new":1};    // Дефолтные значения
//                newID = $$("list_"+ objID).add(defaults, 0, selected_id);     // создаем новую запись
//                // заносим новый ид в переменную.
//                $$("list_"+ objID).getParentView().config.newID = newID;

                // Переход к редактированию
                $$(self.formID + "__rs").show();
                $$("list_" + self.objID).select( $$("list_"+ self.objID).add(defaults, 0, selected_id) );
            }
        },
        {
            view: "button",
            type: "iconButton",
            icon: "group",
            label: "New",
            width: 70,
            click: function () {
                // Если кнопка нажата не на списке  - выходим
                if (! self.isActiveCell_List(self.objID)) {
                    webix.message({ type: "error", text: "Кнопки в этой области не работают" });
                    return false;
                }

                defaults = self.addButtonClick();

                if( defaults == false )   return false;

                defaults["is_new"] = 1;
                defaults["active"] = 1;

//                newID = $$("list_groups_second").add(defaults);     // создаем новую запись
//                // заносим новый ид в переменную.
//                $$("list_groups_second").getParentView().config.newID = newID;

                // Переход к редактированию
                $$(self.formID + "__txt").show();
                $$("list_" + self.objID).select( $$("list_"+ self.objID).add(defaults) );
            }
        },
        {
            view: "button",
            type: "iconButton",
            icon: "trash-o",
            label: "Del",
            width: 70,
            click: function () {
                // Если кнопка нажата не на списке - выходим
                if (! self.isActiveCell_List(self.objID)) {
                    webix.message({ type: "error", text: "Кнопки в этой области не работают" });
                    return false;
                }

                var selected_item = $$("list_" + self.objID).getSelectedItem();

                webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
                    //  тут надо отослать данные на сервер
                    if (result) {

                        webix.ajax().post("/"+ self.hreflink +"/delEntry/", {id: selected_item['id'], group_id: selected_item['$parent']}, function (text, xml, xhr) {
                            if (!text) {
                                webix.message("ОK"); // server side response
                                $$("list_"+ self.objID).remove(selected_item['id']);
                            }
                            else
                                webix.message({type: "error", text: text});
                        })
                    }
                }})
            }
        }
    );

    this.rows[1].cells.push(
        {
            id: this.formID +"__txt",
            view: "form",
            elementsConfig: {labelWidth: 130},
            elements: this.formElements,
            rules: this.formRules,
            save_form: function(){
                var mForm = $$(this.id);

                var values =  mForm.getValues();

                if(values.is_new == undefined)
                    values.is_new = 0;

                if ( mForm.save() === false)  return false;

                // Если не новая запись - убираем признак новой записи
                mForm.setValues({is_new:0},true);

                webix.ajax().post("/" + self.hreflink + "/savegroup", values,
                    function(response){
                        if(response)
                            webix.message({type:"error", expire: 3000, text: response}); // server side response
                        else {
                            webix.message("ОK"); // server side response
                            mForm.getParentView().back();
                            $$("list_" + self.objID).scrollTo(0, values.id);
                        }
                    }
                );
            },
            cancel: function() {
                mView = $$(this.id).getParentView();
                values = $$(this.id).getValues();
                if (values.is_new) {
                    $$("list_" + self.objID).remove( values.id );
                }
                mView.back();
            }
        },
        {
            id: this.formID +"__rs",
            view: "form",
            elementsConfig: {labelWidth: 130},
            elements: this.formElements_rs,
            rules: this.formRules_rs,
            save_form: function(){
                var mForm = $$(this.id);

                var values =  mForm.getValues();

                if(values.is_new == undefined)
                    values.is_new = 0;
                // Если не новая запись - убираем признак новой записи
                mForm.setValues({is_new:0},true);

                if ( mForm.save() === false)  return false;

                webix.ajax().post("/" + self.hreflink + "/savegroup", values,
                    function(response){
                        if(response)
                            webix.message({type:"error", expire: 3000, text: response}); // server side response
                        else {
                            webix.message("ОK"); // server side response
                            mForm.getParentView().back();
                        }
                    }
                );
            },
            cancel: function() {
                mView = $$(this.id).getParentView();
                values = $$(this.id).getValues();
                if (values.is_new) {
                    $$("list_" + self.objID).remove( values.id );
                }
                mView.back();
            }
        }
    );
};

function LogsView(setup) {
    var self            = this;
    extend(MAdmView, MView);    // Наследуем
    MView.apply(this, arguments);  // Запускаем родительский конструктор

    this.formElements    = setup.formElements || [];
    this.hideStartButton = ! setup.showStartButton;
    this.isHideToolbar = setup.isHideToolbar,
    this.columnConfig  = setup.columnConfig || [{}],
    this._startDate = 0;
    this.rows = [
        {
            view: "toolbar",
            height: 35,
            hidden: this.isHideToolbar,
            cols: [
                {
                    view: "label",
                    label: this.toolbarlabel
                },
                {
                    view:"toggle",
                    type:"iconButton",
                    icon:"play",
                    label:"Старт",
                    width: 90,
                    hidden: this.hideStartButton,
                    click: function(){

                        var id = self.list_view + "_" + self.objID;

                        if(this.config.icon == "play") {

                            $$(id).clearAll();
                            self._startDate = ( new Date()).toLocaleFormat('%y-%m-%d %H:%M:%S');
                            this.define({icon:"stop", label: "Стоп"});

                            intervalID = setInterval(function(){
                                webix.ajax().get('/logs/tail/',{'startDate': self._startDate}, function(response) {
                                    data = response.json();
                                    if(data.len ) {
                                        self._startDate = data[(len-1)].ReceivedAt;
                                        $$(id).parse(data);
                                        $$(id ).scrollTo(0,9999);
                                    }
                                });
                            }, 3000);
                        }
                        else {
                            this.define({icon:"play", label: "Старт"});
                            clearInterval(intervalID);
                        }
                    }
                }
            ]
        },
        {
            view: this.list_view,
            id: this.list_view + "_" + this.objID,
            on: this.list_on,
            elementsConfig: {labelWidth: 130},
            elements: this.formElements,
            scroll: this.isScroll,
            scrollX: false,
            columns: this.columnConfig,
            scheme: this.list_scheme
        }
    ];
};