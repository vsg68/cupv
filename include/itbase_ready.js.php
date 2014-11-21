
// Связка формы и представления
<?php if( $permissions == $WRITE_LEVEL ): ?>
//
    ITBasePage.list_bind();

    DataPage.list_bind();

  //   $$("list_itemdata").attachEvent("onBindUpdate", function(obj){
  //   	item = $$("list_itemdata").getSelectedItem();
  //   	if(obj.datatype == "1") {
  //   		item.fields[0].name = obj.name;
  //   		item.fields[0].label = obj.label;
  //   	}
  //   	if(obj.datatype == "2") {
  //   		item.fields[0].name = obj.name;
  //   		item.fields[0].label = obj.label;
  //   	}
  //   });
    
  //   $$("itdata_1").attachEvent("onBindApply", function(obj){
		// $$("itdata_1").setValues({label: obj.fields[0].label, name: obj.fields[0].name}, true);
  //   });

  //   $$("itdata_2").attachEvent("onBindApply", function(obj){
		
		// $$("itdata_2").setValues({label: obj.fields[0].label, name: obj.fields[0].name}, true);
  //   });
    
   
<?php endif; ?>



   $$("list_itbase").filter("#tsect#",2);
   	