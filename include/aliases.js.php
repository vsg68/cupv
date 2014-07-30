var aliasToolBar = new ToolBar("Пересылка/Псевдонимы", "aliases");
aliasToolBar.cols.push({ id: 'filter_mbox', view: 'text', css:"filter", placeholder: 'Filter..', width: 200});

//  Вывод пользователей
var laliases = {
    id: 'list_aliases',
    view: "list",
    template: function (obj) {
        var tmpl;
        tmpl = "<div class='fleft alias_name isactive_" + obj.active + "'>" + obj.alias_name + "</div>";

        icon = obj.from_username ? "fa-user" : "fa-question";
        tmpl += "<div class='fleft arrow'><div class='" + icon + " fleft webix_icon' title='" + obj.from_username + "'></div>";
        tmpl += "<div class='fleft fa-arrow-right webix_icon noborder'></div>";
        icon = obj.to_username ? "fa-user" : "fa-question";
        tmpl += "<div class='" + icon + " fleft webix_icon' title='" + obj.to_username + "'></div></div>";

        tmpl += "<div class='fleft alias_name isactive_" + obj.active + "'>" + obj.delivery_to + "</div>";

        return tmpl;
    },
    type: { height: 40 },
    select: true,
    css: "ftab",
    on: {
        "onKeyPress": function (key) {
            keyPressAction(this, key);
        },
        "onAfterLoad": function () {
            this.config.height = (window.innerHeight - 140);
//            if (window.innerWidth < 1500)  // 8-) minWidth
//                this.config.width = 800;
            this.resize();
        }
    },
    url: "/aliases/showTable/"
};

// Группы
var groupsToolBar = new ToolBar("Группы/Пользователи", "aliases");
var grptree = { id:"list_groups", view:"tree", css:"groups", select:true, scroll:false,
                url: "/groups/showTree/",
                template: function(obj, com){   // Подставляем свою иконку для группы
                    var icon = obj.$parent ? com.folder(obj, com) : "<div class='webix_tree_folder'></div>";
                    return com.icon(obj, com) + icon + '<span>'+ obj.value + '</span>';
                },
                on:{
                    "onKeyPress": function (key) {
                        formId = ( this.getSelectedItem()['$parent'] ) ? "form_groups_RS" : "form_groups_Txt";
                        keyPressAction(this, key, formId);
                    }
                },
                rules: {
                    
                }

};

<?php //if( $permissions == $WRITE_LEVEL ): ?>
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

        // заполняю дефолтными значениями
        // is_new - вспомогательное поле, которое проверяется на стороне сервера
        defaults      = {"active":1, "is_new":1};

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
var delGrp = {
    view: "button", type: "iconButton", icon: "minus", label: "Del", width: 70,
    click: function () {

        // Если кнопка нажата не на списке - выходим
        if (!isActiveCell_List("groups")) {
            webix.message({ type: "error", text: "Кнопки в этой области не работают" });
            return false;
        }

        var selected_item = $$("list_groups").getSelectedItem();

        webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
            //  тут надо отослать данные на сервер
            if (result) {

                webix.ajax().post("/groups/delEntry/", {id: selected_item['id'], group_id: selected_item['$parent']}, function (text, xml, xhr) {
                    if (!text)
                        $$("list_groups").remove(selected_id);
                    else
                        webix.message({type: "error", text: text});
                })
            }
        }})
    }
};
var addUsr = {
    view: "button", type: "iconButton", icon: "user", label: "New", width: 70,
    click: function () {
        var selected_item = $$("list_groups").getSelectedItem();

        // Если кнопка нажата не на списке  - выходим, если не выделено ничего - тоже выходим
        if (!isActiveCell_List("groups") || selected_item == undefined) {
            webix.message({ type: "error", text: "Выделите группу, в которую будем добавлять пользователя" });
//            webix.message({ type: "error", text: "Кнопки в этой области не работают" });
            return false;
        }

        selected_id = selected_item.id;
        // если узел не является корнем, то ищем ID его корня
        if( $$('list_groups').getParentId(selected_id) )
            selected_id = $$('list_groups').getParentId( selected_id );

        defaults = {"value":"", "is_new":1};    // Дефолтные значения
        newID = $$("list_groups").add(defaults, 0, selected_id);     // создаем новую запись
        // заносим новый ид в переменную.
        $$("list_groups").getParentView().config.newID = newID;

        // Переход к редактированию
        $$("form_groups_RS").show();
        $$("list_groups").select(newID);
    }
};
var addGrp = {
    view: "button", type: "iconButton", icon: "group", label: "New", width: 70,
    click: function () {
        // Если кнопка нажата не на списке  - выходим
        if (!isActiveCell_List("groups")) {
            webix.message({ type: "error", text: "Кнопки в этой области не работают" });
            return false;
        }
        // заполняю дефолтными значениями
        // is_new - вспомогательное поле, которое проверяется на стороне сервера
        defaults      = {"active":1, "is_new":1};

        newID = $$("list_groups").add(defaults);     // создаем новую запись
        // заносим новый ид в переменную.
        $$("list_groups").getParentView().config.newID = newID;

        // Переход к редактированию
        $$("form_groups_Txt").show();
        $$("list_groups").select(newID);
    }
};
var save_cancel_button = { margin: 5, cols: [{},
                                            { view: "button", value: "Cancel", width: 70, click: "cancel()" },
                                            { view: "button", value: "Save", width: 70, type: "form", click: "save_form_group" },
                                            {}]};
/*********   Aliases  ********/
// добавляем "свободное место" и кнопки на тулбар
aliasToolBar.cols.push({}, webix.copy(buttonPlus), webix.copy(buttonMinus));
var aliasForm = new mViewAdm("aliases_mv");
aliasForm.cells[0] = laliases;

/******* Groups *******/
groupsToolBar.cols.push(addGrp,addUsr,delGrp);

// делаю несколько форм для разных bind
var dformRS = {id: "form_groups_RS", view: "form", elementsConfig: {labelWidth: 110}, elements: [
                { view: "richselect", label: "Пользователи", name: "value", options:"/users/getMailboxes",
                    on: {
                        "onChange": function(){
                            optId = $$(this.data.suggest).getMasterValue();
                            selected_item = $$(this.data.suggest).getList().getItem(optId);
                            // поле optId - ID выбранной опции
                            if( ! optId || selected_item == undefined) return false;
                            Form = this.getFormView();
                            // заполняем поле user_id при изменении select
                            this.getFormView().setValues({ user_id: selected_item.user_id},true);
                        }
                    }
                },
                webix.copy(save_cancel_button),
                {}
            ]};
var dformTxt = {id: "form_groups_Txt", view: "form", elementsConfig: {labelWidth: 110}, elements: [
                {view: "text", label: "Группы", name: "value" },
                {view: "checkbox", label: "Активно", name: "active"},
                webix.copy(save_cancel_button),
                {}
            ]};

var groupsForm = {
    view: "multiview",
    newID: "",
    abbreviate: "groups",
    cells: [ grptree, dformRS, dformTxt ] };

<?php //else: ?>

/*********   Aliases  ********/
//var aliasForm = new mView("aliases_mv");

<?php //endif; ?>

/******************************************** For ALL ***********************************************/
//aliasForm.cells[0] = laliases;

maintable = {
    cols: [
        {rows: [ aliasToolBar, aliasForm ], minWidth:550},
        {rows: [ groupsToolBar, groupsForm]},
        {}
    ]
};


//TODO
