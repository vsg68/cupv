// Функция наследования на классах
function extend(Child, Parent) {
    var F = function() { }
    F.prototype = Parent.prototype
    Child.prototype = new F()
    Child.prototype.constructor = Child
    Child.superclass = Parent.prototype
}

var save_cancel_button = {
    rows:[
            {height: 30},
            {
                margin: 5,
                cols: [
                    {},
                    { view: "button", value: "Cancel", width: 70, click: function(){ this.getFormView().config.cancel()} },
                    { view: "button", value: "Save", width: 70, type: "form", click: function(){ this.getFormView().config.save_form()} },
                    {}
                ]
            },
            {}
    ]
};

// Strip whitespace (or other characters) from the beginning and end of a string
// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// +   improved by: mdsjack (http://www.mdsjack.bo.it)
// +   improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
// +      input by: Erkekjetter
// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
function trim( str, charlist ) {    

    charlist = !charlist ? ' \s\xA0' : charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
    var re = new RegExp('^[' + charlist + ']+|[' + charlist + ']+$', 'g');
    return str.replace(re, '');
}

// Проверка на существования адреса и id, а так же правильность домена
function checkEmail(ID, value) {
    var valid = false;
    var mForm = $$(ID).getValues();
    if (webix.rules.isEmail(value)) {
        webix.ajax().sync().get("/users/validateEmail/", { mbox: mForm.mailbox, id: mForm.id }, function (response) {
            if (response != "0")
                webix.message({type: "error", expire: 3000, text: "Проверьте адрес и домен"});
            else
                valid = true;  // response
        });
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
    domain_tmpl = "^([\\w\\d-]+\\.)+[\\w\\d-]+$";
    date_tmpl = "^\\d{4}-\\d{2}-\\d{2}$";
    int_tmpl = "^\\d+$";
    ip_tmpl = "(\\d{1,3}\\.){3}\\d{1,3}$";

    switch (type) {
        case 'mail':
            reg = new RegExp(mail_tmpl, 'i');
            break;
        case 'nets':
            reg = new RegExp(net_tmpl, 'i');
            break;
        case 'ip':
            reg = new RegExp(ip_tmpl, 'i');
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

function GeneratePassword (num_var){
    
            if(!num_var)
                num_var = 7;

            passwd = '';
            str = "OPQRSrstuvwxTUVWXYZ0123456789abcdefjhigklmABCDEFJHIGKLMNnopqyz_=-";

            for(i=0;i<num_var;i++) {
                n = Math.floor(Math.random() * str.length);
                passwd += str[n];
            }
            return passwd;
}

Date.prototype.toLocaleFormat = function(format) {
    var f = {
                Y : this.getFullYear(),
                y : this.getFullYear()-(this.getFullYear()>=2e3?2e3:1900),
                m : this.getMonth() + 1,
                d : this.getDate(),
                H : this.getHours(),
                M : this.getMinutes(),
                S : this.getSeconds()
            }, k;

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
    var self             = this;
    this.objID           = setup.id;  // Общее название
    this.hreflink        = setup.hreflink || setup.id.split("_")[0];  // Общее название
    this.isScroll        = setup.isListScroll;
    this.toolbarlabel    = setup.toolbarlabel || "";
    this.hideSearchField = ! setup.showSearchField;
    this.hideActiveOnly  = ! setup.showActiveOnly;
    this.filterFunction  = setup.filterFunction || (function(){return true});
    this.list_view       = setup.list_view || "list";
    this.list_css        = setup.list_css || "ftab";
    this.list_url        = setup.list_url || "";
    this.list_scheme     = setup.list_scheme || {};
    this.list_template   = setup.list_template || "<div class='isactive_#active#'>#name#</div>";  // Шаблон для отображения в list
    this.list_on         = setup.list_on || {};
    this.contextmenu     = setup.list_Edit;
    this.cmenuRules      = setup.list_EditRules;
    this.hideTabbar      = ! setup.showTabbar;
    this.columns         = setup.list_columns;
    this.fixedRowHeight  = setup.fixedRowHeight;

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
            hidden: this.hideTabbar,
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
                    id            : "list_" + self.objID,
                    view          : this.list_view,
                    scheme        : this.list_scheme,
                    scroll        : this.isScroll,
                    css           : this.list_css,
                    type          : { height: "auto" },
                    select        : true,
                    template      : this.list_template,
                    url           : this.list_url,
                    on            : this.list_on,
                    onContext     : {},
                    contextmenu   : this.contextmenu,
                    cmenuRules    : this.cmenuRules,
                    columns       : this.columns,
                    fixedRowHeight: this.list_fixedRowHeight
                }
            ]
        }
    ]
};

function MAdmView(setup) {
    var self            = this;
    extend(MAdmView, MView);    // Наследуем
    MView.apply(this, arguments);  // Запускаем родительский конструктор

    this.savefunct      = setup.savefunct || "save";
    this.formID         = setup.formID || "form_" + this.objID;
    this.formElements   = setup.formElements || [];
    this.formRules      = setup.formRules || {};

    this.list_bind      = setup.list_bind || function(){
                                                for(i=0; i < self.rows[1].cells.length; i++) {
                                                    if( self.rows[1].cells[i].view == "form" )
                                                        $$(self.rows[1].cells[i].id).bind('list_' + self.objID);
                                                }
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
                if( ! (self.isEnableAddButton && self.isActiveCell_List()) )
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
                if( ! (self.isEnableDelButton && self.isActiveCell_List()) )
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

    this.rows[1].cells[0].contextmenu = setup.list_Edit  || {
                                                                Add   : function(){
                                                                             defaults = { "is_new":1, "active":1};
                                                                             // не показываем richselect, если кладем объект в корень
                                                                             $$("form_" + self.objID).show();    
                                                                             // Переход к редактированию
                                                                             $$("list_" + self.objID).select( $$("list_" + self.objID).add(defaults) );
                                                                        },
                                                                Edit  : function(){ $$("form_" + self.objID).show();},
                                                                Delete: function(){
                                                                                var selected_id = $$("list_" + self.objID).getSelectedId();
                                                                                webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
                                                                                    //  тут надо отослать данные на сервер
                                                                                    if (result) {
                                                                                        webix.ajax().post("/" + self.hreflink + "/delEntry/", {id: selected_id}, function (text, xml, xhr) {
                                                                                            if (!text)
                                                                                                $$("list_" + self.objID).remove(selected_id);
                                                                                            else
                                                                                                webix.message({type: "error", text: text});
                                                                                        })
                                                                                    }
                                                                                }})   
                                                                        },
                                                               };

    this.rows[1].cells[0].cmenuRules  = setup.list_EditRules || function(key){
                                                                    var selected_item = $$("list_"+ self.objID).getSelectedItem();
                                                                     
                                                                    if( !selected_item ){
                                                                        if( key == "Delete" || key == "Edit") 
                                                                            return false;
                                                                    }
                                                                    return true;
                                                                };                                                 
                                
    formPages = setup.formPages || [];
    // определение страницы с формой и ее добавление
    for( i=0; i<formPages.length; i++) {

        this.rows[1].cells.push({
                view          : "form",
                id            : formPages[i].formID || "form_" + this.objID,
                elementsConfig: formPages[i].elementsConfig || {labelWidth: 130},
                elements      : formPages[i].formElements,
                rules         : formPages[i].formRules,
                on            : formPages[i].on || {},
                                                    //    "onKeyPress": function(key){
                                                    //         x = this;
                                                    //         if( key == 27)
                                                    //             this.getParentView().back();
                                                    //     },
                                                    //     "onChange" : function(){ alert("!")}
                                                    // },
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

                                                            webix.ajax().post("/" + self.hreflink + "/" + self.savefunct, values,
                                                                function(response){
                                                                    if(response)
                                                                        webix.message({type:"error", expire: 3000, text: response}); // server side response
                                                                    else {
                                                                        webix.message("ОK"); // server side response
                                                                        mForm.getParentView().back();
                                                                        //l = $$("list_" + self.objID).config.view;
                                                                        // $$("list_" + self.objID).showItem(values.id);
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
    extend(LogsView, MView);    // Наследуем
    MView.apply(this, arguments);  // Запускаем родительский конструктор

    this.formElements    = setup.formElements || [];
    this.hideStartButton = ! setup.showStartButton;
    this.isHideToolbar   = setup.isHideToolbar,
    this.columnConfig    = setup.columnConfig || [{}],
    this._startID        = 0;
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
            view          : this.list_view,
            id            : this.list_view + "_" + this.objID,
            on            : this.list_on,
            scheme        : this.list_scheme,
            elementsConfig: {labelWidth: 130},
            elements      : this.formElements,
            columns       : this.columnConfig,
        }
     ];
};

function LogsPoll(setup) {
    var self            = this;
    extend(LogsPoll, MView);    // Наследуем
    MView.apply(this, arguments);  // Запускаем родительский конструктор

    this.formElements    = setup.formElements || [];
    this.hideStartButton = ! setup.showStartButton;
    this.isHideToolbar   = setup.isHideToolbar,
    this.columnConfig    = setup.columnConfig || [{}],
    this.evtSource       = false;
    
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

                        if(typeof(EventSource) == undefined) {
                            webix.message("Ваш браузер не поддерживает технологию SSE.. Пора обновить браузер.");
                            return true;
                        }

                        var id = self.list_view + "_" + self.objID;
                        
                        if( self.evtSource ) {
                            self.evtSource.close();
                            self.evtSource = false;
                            this.define({icon:"play", label: "Старт"});
                            $$("searchButton").define({disabled:false});
                        }
                        else {
                            this.define({icon:"stop", label: "Стоп"});
                            $$("searchButton").define({disabled:true});
                            $$(id).clearAll();
                            
                            self.evtSource = new EventSource("/logs/sse");
                                                    
                            self.evtSource.onmessage = function(e){
                                                            $$(id).parse(e.data);
                                                        };
                            self.evtSource.onerror = function(e) {
                                                            if (this.readyState == EventSource.CONNECTING)      
                                                                webix.message({type: "error", text:"Соединение порвалось, пересоединяемся..."});
                                                            else
                                                                webix.message({type: "error", text:"Ошибка, состояние: " + this.readyState});
                                                        };  
                        }                        
                    }
                }
            ]
        },
        {
            view          : this.list_view,
            id            : this.list_view + "_" + this.objID,
            on            : this.list_on,
            scheme        : this.list_scheme,
            elementsConfig: {labelWidth: 130},
            elements      : this.formElements,
            columns       : this.columnConfig,
        }
     ];
};

/* 
    Контекстное меню. 
    Входные параметры:
     - ID вьюхи, к чему будем привязываться
     - Индекс в массиве меню, где будет разделитель (украшательство)
*/
function CMenu(setup){
    var self = this;
    this.separator_index = setup.separator_index || -1;
    var menuItems = $$(setup.listID).config.contextmenu || {};

    // заполняем список меню по существующим функциям в мастер-объекте
    function printMenuItems() {  
                          var data = [];
                          var i = 0;
                          for (key in menuItems) {
                               
                              if( i == self.separator_index )
                                  data.push({ $template:"Separator" });
                                
                              data.push(key);
                              i++;
                          }
                          return data;
    };
    // объект меню
    this.menu = {
        view:"contextmenu",
        data: printMenuItems(),
        master: $$(setup.listID)["$view"],
        on:{
            "onItemClick": function(id){
                var selectedId = $$(setup.listID).getSelectedId();
                var item       = this.getItem(id).value;

                if( menuItems[item] != undefined  && ! this.hasCss(id,"webix_disabled"))
                    menuItems[item]();
            },
            "onShow": function(){
                if( $$(setup.listID).config.cmenuRules == undefined )
                    return true;
                
                // Проходим по всем пунктам меню и применяем к ним правила
                for( i=0; i<this.count(); i++) {
                    
                    var iid   = this.getIdByIndex(i);
                    var value = this.getItem(iid).value;

                    if( $$(setup.listID).config.cmenuRules( value ))
                        this.enableItem(iid);
                    else
                        this.disableItem(iid);
                }
            }
        }
    };
};
