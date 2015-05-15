
<?php if( $permissions == $WRITE_LEVEL ): ?>

/*********   USER PAGE  ********/

var DNSPage = new PageTreeAdm({
    id          : "dns",
    list_view   : "treetable",
    list_css    : "dns_zone", 
    list_columns: [
        {id:"value",header:"Name",width:250, template: function(obj,com){
                // Подставляем свою иконку для группы
                var icon = obj.$parent ? "<div class='webix_tree_file isactive_" + obj.active + "'></div>" : "<div class='webix_tree_folder'></div>";
                return com.icon(obj, com) + icon + '<span class="isactive_' + obj.active + '">'+ obj.value + '</span>';
            },
        },
        {id:"type",header:"Type",width:100},
        {id:"content",header:"Content", fillspace:true},
        {id:"prio",header:"Priority",width:100},
        {id:"ttl",header:"TTL",width:100},
    ],
    list_Edit    :{
        Add_Domain: function(){
                             defaults = {
                                         "is_new": 1, 
                                         "type"  : "NATIVE", 
                                         "id"    : GenID()
                                        };

                             // Переход к редактированию
                             $$("dns__txt").show(); 
                             $$("list_dns").select( $$("list_dns").add( defaults ) );   
                    },
        Add_Item  : function(){
                                selected_id = $$("list_dns").getSelectedId()["id"];
                                parent_id = $$("list_dns").getItem(selected_id)["$parent"] || selected_id;

                                // если это не бранч - тогда нужен id бранча
                                defaults = {
                                             "id"    : GenID(),
                                             "is_new": 1, 
                                             "domain_id": parent_id,
                                             "dname": $$("list_dns").getItem(parent_id)["value"],
                                             "type": "A", 
                                             "prio": 0, 
                                             "ttl": 86400, 
                                             "active": 1, 
                                            };

                                 // не показываем richselect, если кладем объект в корень
                                 $$("dns__txt0").show();     
                                 // Переход к редактированию
                                 $$("list_dns").select( $$("list_dns").add( defaults, -1, parent_id) );
                    },
        Edit      : function(){
                            var selected_id   = $$("list_dns").getSelectedId()["id"];
                            var selected_item = $$("list_dns").getItem(selected_id);
                            
                            if( selected_item["$parent"] == 0) 
                                $$("dns__txt").show();
                            else
                                $$("dns__txt0").show();
                    },
        Delete    : function(){
                            var selected_id   = $$("list_dns").getSelectedId()["id"];
                            var selected_item = $$("list_dns").getItem(selected_id);

                            webix.confirm({text: "Уверены, что надо удалять?", callback: function (result) {
                                //  тут надо отослать данные на сервер
                                if (result) {
                                    webix.ajax().post("/"+ DNSPage.hreflink +"/delEntry/", selected_item, function (text, xml, xhr) {
                                        if (!text) {
                                            webix.message("ОK"); // server side response
                                            $$("list_dns").remove(selected_item['id']);
                                        }
                                        else
                                            webix.message({type: "error", text: text});
                                    })
                                }
                            }})     
                    },
    },    
    list_EditRules: function(key){
        var selected_id   = $$("list_dns").getSelectedId();
                 
        if( !selected_id ){
            if( key == "Add_Item" || key == "Delete" || key == "Edit" || key == "Copy") 
                return false;
        }
        else {
            selected_item = $$("list_dns").getItem(selected_id["id"]);
            if( selected_item['$count'] && key == "Delete" )
                return false;

            if( !selected_item['$count'] && key == "Copy" )
                return false;

        }
        return true;
    },         
    formPages: [
        {
            formID: "dns__txt",
            formElements: [
                {view: "text", label: "Домен", name:"value"},
                // {view: "richselect", label: "Мастер", name: "master", options:["MASTER","SLAVE"] },
                webix.copy(save_cancel_button)
            ],
            formRules:{
                value: function(value){
                    return fnTestByType("domain",value);
                },
            }
        },
        {
            formID: "dns__txt0",
            formElements: [
                {view: "richselect", label: "Тип", name: "type", id: "type_entry", options:["SOA","NS","MX","A","PTR","CNAME","HINFO","TXT"], 
                    on:{
                        onChange: function(value){
                                        form   = this.getFormView();
                                        values = form.getValues();

                                        // не даем вводить имена компа - вместо него выступает имя домена
                                        if( value == "MX" || value == "NS" || value == "SOA" && values["dname"]) {
                                            $$("entryname").setValue(values["dname"]);
                                            $$("entryname").define("readonly",true);
                                        }
                                        else {
                                            $$("entryname").define("readonly",false);
                                        }

                                        // скрываем поле - оно нужно только для почтовика
                                        if( value == "MX" ){
                                            if( !values["prio"] ) {  // если 0, то ставим 10-ку по умолчанию
                                                $$("prio").setValue(10);
                                            }
                                            $$("prio").show();
                                        }
                                        else {
                                            $$("prio").setValue(0);
                                            $$("prio").hide();
                                        }

                                        $$("entryname").refresh();

                        }    
                    }
                },
                {view: "text", label: "Name", name:"value", id:"entryname"},
                {view: "text", label: "Content/IP", name: "content", id: "content", 
                    on: {
                        onItemClick: function(){
                                    if( $$("type_entry").getValue() == "SOA" )
                                        $$("soa_entry").show(this.getNode());
                        },
                    }
                },
                {view: "counter", css:"popup_couter", label: "Priority", name: "prio", id:"prio", min:1, hidden: true },
                {view: "counter", css:"popup_couter", label: "TTL", name: "ttl", min:100 },
                {view: "checkbox", label: "Active", name: "active" },
                webix.copy(save_cancel_button)
            ],
            formRules:{
                $all   : webix.rules.isNotEmpty,
                content: function(value){
                                        var type  = $$("type_entry").getValue();

                                        if( type == "SOA" || type == "HINFO" || type == "TXT" ) 
                                            return true;

                                        if( ! (fnTestByType("ip", value) || fnTestByType("domain",value) ) )
                                            return false;
                                        
                                        return true;    
                                    },
            },
        }
    ],
    list_on: {
        "onKeyPress": function (key) {
            var selected_item = this.getItem(this.getSelectedId()["id"]);
            formID        = selected_item["$parent"] == "0"  ? "dns__txt" : "dns__txt0";
            DNSPage.keyPressAction(this, key, formID);
        },
    },
    list_url: "/dns/getTree/"
});



<?php else: ?>

var DNSPage = new MView({
    id          : "dns",
    list_view   : "treetable",
    list_css    : "dns_zone", 
    list_columns: [
        {id:"value",header:"Name",width:250, template: function(obj,com){
                // Подставляем свою иконку для группы
                var icon = obj.$parent ? "<div class='webix_tree_file isactive_" + obj.active + "'></div>" : "<div class='webix_tree_folder'></div>";
                return com.icon(obj, com) + icon + '<span class="isactive_' + obj.active + '">'+ obj.value + '</span>';
            },
        },
        {id:"master",header:"Master",width:100},
        {id:"type",header:"Type",width:100},
        {id:"content",header:"Content", fillspace:true},
        {id:"prio",header:"Priority",width:100},
        {id:"ttl",header:"TTL",width:100},
    ],
    list_url: "/dns/getTree/"
});

<?php endif; ?>

/*********   USER PAGE  ********/
/******************************************** For ALL ***********************************************/
maintable = {
    rows: [DNSPage]
};

