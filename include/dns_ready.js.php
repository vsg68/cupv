
// Связка формы и представления
<?php if( $permissions == $WRITE_LEVEL ): ?>
//
    DNSPage.list_bind();
  
    // $$("list_dns").define({fixedRowHeight:false });
    // $$("list_dns").define({resizeColumn:true });

	// Контекстное меню
	// var CM_DNS = new CMenu({listID: "list_dns", separator_index: 4});
	var CM_DNS = new CMenu({listID: "list_dns"});
    
	// Инициализация контекстного меню
	webix.ui(CM_DNS.menu);
	
	webix.event($$("list_dns").$view, "contextmenu", function(e){
	  e.preventDefault();
	})

	webix.ui({
		view:"popup",
		id: "soa_entry",
        body: {
			view      :"form",
			id 		  :"popup_form",
			width     :400,
			elementsConfig: {labelWidth: 130},
			elements: [
					{view: "text", label: "Zone place", name: "zplace" },
					{view: "text", label: "Conact Email", name: "cemail" },
					{view: "text", label: "Serial", name: "serial", readonly: true },
                    {view: "counter", css:"popup_couter", label: "Refresh", name: "refresh", min: 3600},
                    {view: "counter", css:"popup_couter", label: "Retry", name: "retry", min: 3600},
                    {view: "counter", css:"popup_couter", label: "Expire", name: "expire", min: 3600},
                    {view: "counter", css:"popup_couter", label: "TTL", name: "ttl", min: 3600},
                    {
		                cols: [
		                    {},
			                    { view: "button", value: "OK", type: "form", width: 70, click: function(){
					                    	var v = $$("popup_form").getValues();
											// v.zplace = v.zplace.replace(/\.$/,'') + ".";
											// v.cemail = v.cemail.replace(/@/,'\.').replace(/\.$/,'') + ".";
											// 
											// Убираем точку в конце записи
											v.zplace = v.zplace.replace(/\.$/,'');
											v.cemail = v.cemail.replace(/@/,'\.').replace(/\.$/,'');
											v.serial = 1 + parseInt(v.serial);	
											$$("content").setValue( v.zplace +" "+ v.cemail +" "+ v.serial +" "+ v.refresh +" "+ v.retry +" "+ v.expire +" "+ v.ttl);
											$$("soa_entry").hide();
		                    			}
		                    	},
		                    {}
		                ]
		            },
			],
			borderless: true,
		},
		on:{
			"onShow": function(id){
				var values = $$("content").getValue().split(" ");
				var num = new Date();
				$$("popup_form").setValues({
											"zplace" :values[0] || "comp.example.org",
											"cemail" :values[1] || "postmaster@example.org",
											"serial" :values[2] || num.toLocaleFormat("%Y%m%d") + "01",
											"refresh":values[3] || 28800,
											"retry"  :values[4] || 7200,
											"expire" :values[5] || 604800,
											"ttl"    :values[6] || 86400,
											});
        	},
		}
	}).hide();

<?php endif; ?>
