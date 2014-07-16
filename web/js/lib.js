
function ToolBar(label, abr){
    this.view = "toolbar";
    this.height = 30;
    this.abr = abr;
    this.cols = [
        {view: "label", label: label},
    ]
}

function mViewAdm(id){
    this.view = "multiview";
    this.abbreviate = id.split("_")[0];
    this.newID;
    this.fitBiggest = true;
    this.minWidth = 250;
    this.maxWidth = 300;
    this.cells = [
        { id: "list_" + this.abbreviate, view: "list",
            linkfield:"", // поле привязки к пользовательскому ящику
            scroll: false, select: true,
            template: "<div class='isactive_#active#'>#alias_name#</div>",
            on: { "onKeyPress": function (key) {
                keyPressAction(this, key);
            }
            }},
        { id: "form_" + this.abbreviate,view:"form",elementsConfig: {labelWidth: 70}, elements: [
            {view: "text", label:"id", name: "id", hidden: true },
            {view: "text", label: "alias", name: "alias_name" },
            {view: "text", label: "forward", name: "delivery_to", hidden: true },
            {view: "checkbox", label: "active", name: "active"},
            { margin: 5, cols: [
                {},
                { view: "button", value: "Cancel", width: 70, click: "cancel()" },
                { view: "button", value: "Save", width: 70, type: "form", click: "save()" },
                {}
            ]},
            {}],
            rules: {
                alias_name: webix.rules.isEmail,
                delivery_to: webix.rules.isEmail
            }
        }
    ]
}

function mView(id){
    this.abbreviate = id.split("_")[0];
    this.maxWidth = 300;
    this.cells = [
        { id: "list_" + this.abbreviate, view: "list",
            linkfield:"", // поле привязки к пользовательскому ящику
            scroll: false, select: true,
            template: "<div class='isactive_#active#'>#alias_name#</div>",
            on: { "onKeyPress": function (key) {
                keyPressAction(this, key);
            }}
        },
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
function save() {
    //    валидация!! {}
        mForm = this.getFormView();
        abrv = (mForm.config.id.split("_"))[1];
        if( mForm.validate() ) {
            webix.ajax().post("/users/" + abrv+ "_save", mForm.getValues(), function(text){
                if( text )
                    webix.message("Request: \n" + text); // server side response
            });
            mForm.save();
            var mView = mForm.getParentView();
            mView.config.newID = "";
            mView.back();
        }
        else {
            if(abrv == "group")
                msg = "Пользователь в данной группе уже присутствует";
            else
                msg = "Что-то в форме не так..";

            webix.message({ type:"error", text: msg });
        }
    }

    // Отмена изменений
    function cancel() {
        var mView = this.getFormView().getParentView();
        var newID = mView.config.newID;
        if( newID ){
            $$("list_" + mView.config.abbreviate).remove(newID);
        }
        mView.back();
    }
    // Проверка на существования адреса и id, а так же правильность домена
    function checkEmail(){
        var valid = false;
        var mForm = $$('form_user').getValues();
        if( webix.rules.isEmail(mForm.mailbox) ) {
        webix.ajax().sync().get("/users/showTable", { q:"valid", mbox: mForm.mailbox, id: mForm.id }, function(text){
        valid = (text == 'null') ? true : false;  // responce
        });
        if(! valid )
          webix.message({type:"error", expire:3000, text:"Проверьте адрес и домен"});
        }
        return valid;
    }
