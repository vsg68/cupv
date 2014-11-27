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
// проверка наличия одинаковых групп
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
// проверка на существовании роли
function chkDublRoles(ID, value) {
    var ok = true;  
    $$("list_" + ID).data.each( function(obj){
        if (obj.name == value) {
            webix.message({ type: "error", text: "Такая роль уже присутствует" });
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
    this.hreflink = setup.hreflink || setup.id.split("_")[0];  // Общее название
    this.isScroll = setup.isListScroll;
    this.toolbarlabel = setup.toolbarlabel || "";
    this.hideSearchField = ! setup.showSearchField;
    this.hideActiveOnly = ! setup.showActiveOnly;
    this.filterFunction = setup.filterFunction || (function(){return true});
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
                    label: this.toolbarlabel,
                    width: 150,
                    hidden: this.toolbarlabel ? false : true
                },
                {
                    id: 'fltr_' + self.objID,
                    view: 'text',
                    placeholder: 'Filter..',
                    width: 200,
                    hidden: this.hideSearchField,
                    on: {
                        "onTimedKeyPress": this.filterFunction
                    }
                },
                {
                    id: 'chkBox_' + self.objID,
                    view: "checkbox",
                    label: "Active",
                    labelWidth:60,
                    hidden: this.hideActiveOnly,
                    value: 1,
                    width: 80,
                    on: {
                        "onChange": this.filterFunction
                    }
                },
                {}

            ]
        },
       {
            view: "multiview",
            fitBiggest: true,
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

    self.savefunct      = setup.savefunct || "save";
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

    this.list_bind      = setup.list_bind || function(){
                                                for(i=0; i < self.rows[1].cells.length; i++) {
                                                    if( self.rows[1].cells[i].view == "form" )
                                                        $$(self.rows[1].cells[i].id).bind('list_' + self.objID);
                                                }
                                             };
    this.isEnableAddButton  = setup.isEnableAddButton || true;
    this.isEnableDelButton  = setup.isEnableDelButton || true;

    this.isActiveCell_List = function() {
        var multiview = $$("list_" + self.objID).getParentView(); // multiview

        if (multiview.config.view != "multiview")
            return false;

        
        if( multiview.getActiveId() != "list_" + self.objID ) {
            webix.message({ type: "error", text: "Кнопки в этой области не работают" });
            return false;
        }

        return true;
    };
    
 };

function PageAdm(setup) {
    var self = this;
    extend(PageAdm,MAdmView);    // Наследуем
    MAdmView.apply(this, arguments);  // Запускаем родительский конструктор

    this.rows[0].cols.push(
        {
            view  : "button",
            type  : "iconButton",
            icon  : "plus",
            label : "New",
            width : 75,
            hidden: this.hideAddButton,
            
            click :  function(){
                // Условие срабатывание кнопки
                if( ! (self.isEnableAddButton() && self.isActiveCell_List()) )
                    return false;

                defaults = self.addButtonClick();

                if( defaults == false )  return false;

                defaults["is_new"] = 1;
                defaults["active"] = 1;
                // Переход к редактированию
                $$("form_" + self.objID).show();
                // создаем новую запись
                $$("list_" + self.objID).select( $$("list_" + self.objID).add(defaults) );
            }
        },
        {
            view  : "button",
            type  : "iconButton",
            icon  : "trash-o",
            label : "Del",
            width : 75,
            hidden: this.hideDelButton,
            click : function(){

                // Если кнопка нажата не на списке - выходим
                if( ! (self.isEnableDelButton() && self.isActiveCell_List()) )
                    return false;
                    

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
            id            : this.formID,
            view          : "form",
            elementsConfig: {labelWidth: 130},
            elements      : this.formElements || [{view: "text", label: "Псевдоним", name: "alias_name" },
                                            {view: "text", label: "Пересылка", name: "delivery_to" },
                                            {view: "checkbox", label: "Активно", name: "active"},
                                            webix.copy(save_cancel_button),
                                            {}],
            rules         : this.formRules,            
            save_form     : setup.save_form || function(){
                                                    var mForm = $$(this.id);

                                                    var values =  mForm.getValues();

                                                    if(values.is_new == undefined)
                                                        values.is_new = 0;

                                                    if ( mForm.save() === false)  return false;

                                                    // Если не новая запись - убираем признак новой записи
                                                    mForm.setValues({is_new:0},true);

                                                    // Исключение для форварда
                                                    self.hreflink = (self.hreflink == "fwd" ) ? "aliases" : self.hreflink;

                                                    webix.ajax().post("/" + self.hreflink + "/" + self.savefunct, values,
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
            cancel       : setup.cancel || function() {
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

    formPages = setup.formPages || [];
    menuButtons = setup.menuButtons || [];
    // определение страницы с формой и ее добавление
    for( i=0; i<menuButtons.length; i++) {

        // Сначала описываем формы
        // количество кнопок = кол-во форм +1
        this.rows[0].cols.push({
                view     : "button",
                type     : menuButtons[i].type || "iconButton",
                label    : menuButtons[i].label || "",
                width    : menuButtons[i].width || 75,
                icon     : menuButtons[i].icon || "user",
                isEnable : menuButtons[i].isEnable || true,
                click    : menuButtons[i].click || function(){},
        });

        if( i >= formPages.length )  continue; 

        this.rows[1].cells.push({
                view          : "form",
                id            : formPages[i].formID || "form_" + this.objID,
                elementsConfig: formPages[i].elementsConfig || {labelWidth: 130},
                elements      : formPages[i].formElements,
                rules         : formPages[i].formRules,
                cancel        : ( formPages[i].cancel || function() {
                                                                mView = $$(this.id).getParentView();
                                                                values = $$(this.id).getValues();
                                                                if (values.is_new) {
                                                                $$("list_" + self.objID).remove( values.id );
                                                                }
                                                                mView.back();
                                                         }),
                save_form     : ( formPages[i].save_form || function() {
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
                                                      })
        });
        

    }
};

function LogsView(setup) {
    var self            = this;
    extend(MAdmView, MView);    // Наследуем
    MView.apply(this, arguments);  // Запускаем родительский конструктор

    this.formElements    = setup.formElements || [];
    this.hideStartButton = ! setup.showStartButton;
    this.isHideToolbar = setup.isHideToolbar,
    this.columnConfig  = setup.columnConfig || [{}],
    this._startID = 0;
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
                            // сделаю по ID
                            self._startID = 0;
                            this.define({icon:"stop", label: "Стоп"});
                            $$("searchButton").define({disabled:true});

                            intervalID = setInterval(function(){

                                webix.ajax().get('/logs/tail/',{'ID': self._startID}, function(data) {

                                    $$(id).parse(data);
                                    var lastid = $$(id).getLastId();
                                    // Если ничего не пришло - выходим
                                    if( ! lastid ) return;   // если долгое время не изменяется !?
                                    self._startID = $$(id).getItem(lastid).ID;
                                    $$(id ).showItem(lastid);
                                });
                            }, 5000);
                        }
                        else {
                            this.define({icon:"play", label: "Старт"});
                            $$("searchButton").define({disabled:false});
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
        }
     ];

 }

function BaseTreeAdm(setup) {

    var self = this;
    extend(BaseTreeAdm,PageTreeAdm);    // Наследуем
    PageTreeAdm.apply(this, arguments);  // Запускаем родительский конструктор
    
    lastnum = this.rows[1].cells.length;

    this.rows[1].cells[(lastnum-1)].save_form = function(){
                            var mForm = $$(this.id);
                            
                            var values =  mForm.getValues();
                            
                            if(values.is_new == undefined)
                                values.is_new    = 0;
                            
                            // Сначала валидация формы - потом отправка
                            if( mForm.validate() === false ) return false;

                            $$("list_" + self.objID).move(values.id,null,null, {parent:values.pid}); 

                            // Если не новая запись - убираем признак новой записи
                            // Важно изменить $parent после MOVE, 
                            // иначе следующее изменение будет брать не правильный парент для перемещения
                            mForm.setValues({is_new:0, "$parent":values.pid },true);

                            mForm.save();
                            
                            webix.ajax().post("/" + self.hreflink + "/savegroup", values,
                                function(response){
                                    if(response)
                                        webix.message({type:"error", expire: 3000, text: response}); // server side response
                                    else {
                                        webix.message("ОK"); // server side response
                                        // открываем бранч, куда переместили листок
                                      
                                        $$("list_" + self.objID).open( values.pid );
                                        $$("list_" + self.objID).scrollTo(0, values.id);
                                        mForm.getParentView().back();
                                    }
                                }
                            );
                        };
                            
};