/*
@license
webix UI v.2.1.0
This software is covered by Webix Commercial License.
Usage without proper license is prohibited.
(c) XB Software Ltd.
*/
webix.i18n.pivot=webix.extend(webix.i18n.pivot||{},{apply:"Apply",cancel:"Cancel",columns:"Columns",count:"count",fields:"Fields",filters:"Filters",max:"max",min:"min",operationNotDefined:"Operation is not defined",pivotMessage:"[Click to configure]",rows:"Rows",select:"select",sum:"sum",text:"text",values:"Values",windowMessage:"[move fields into required sector]"}),webix.protoUI({name:"pivot",version:"2.1.0",defaults:{fieldMap:{},yScaleWidth:300,columnWidth:150,filterLabelWidth:100},$divider:"_'_",$init:function(t){t.structure||(t.structure={}),webix.extend(t.structure,{rows:[],columns:[],values:[],filters:[]}),this.$view.className+=" webix_pivot",webix.extend(t,this.Iu(t)),this.$ready.push(this.render),this.data.attachEvent("onStoreUpdated",webix.bind(function(){this.$$("data")&&this.render()
},this))},Iu:function(t){var e={id:"filters",view:"toolbar",hidden:!0,cols:[{}]},i={view:"treetable",id:"data",select:"row",navigation:!0,leftSplit:1,resizeColumn:!0,on:{onHeaderClick:function(t){0===this.getColumnIndex(t.column)&&this.getTopParentView().configure()}},columns:[{}]};return t.datatable&&"object"==typeof t.datatable&&(delete t.datatable.id,webix.extend(i,t.datatable,!0)),{rows:[e,i]}
},configure:function(){if(!this.Ju){var t={id:"popup",view:"webix_pivot_config",operations:[],pivot:this.config.id};webix.extend(t,this.config.popup||{}),this.Ju=webix.ui(t),this.Ju.attachEvent("onApply",webix.bind(function(t){this.define("structure",t),this.render()},this))}var e=[];for(var i in this.operations)e.push({name:i,title:this.Ku(i)});
this.Ju.define("operations",e);var s=webix.html.offset(this.$$("data").getNode());this.Ju.setPosition(s.x+10,s.y+10),this.Ju.define("data",this.getFields()),this.Ju.show()},render:function(t){var e=this.Lu(this.data.pull,this.data.order);if(!t){var i=this.Mu();i.length>0?(this.$$("filters").show(),this.$$("filters").define("cols",i),this.Nu()):this.$$("filters").hide()
}this.$$("data").config.columns=e.header,this.$$("data").refreshColumns(),this.$$("data").clearAll(),this.$$("data").parse(e.data)},toPDF:function(){this.$$("data").exportToPDF.apply(this.$$("data"),arguments)},toExcel:function(){this.$$("data").exportToExcel.apply(this.$$("data"),arguments)},Ku:function(t){return webix.i18n.pivot[t]||t
},Ou:function(t){return this.config.fieldMap[t]||t},Mu:function(){for(var t=this.config.structure.filters||[],e=[],i=0;i<t.length;i++){var s=t[i],n={value:s.value,labelAlign:"right",label:this.Ou(s.name),labelWidth:this.config.filterLabelWidth,field:s.name,view:s.type};"select"==s.type&&(n.options=this.Pu(s.name)),e.push(n)
}return e},Pu:function(t){var e=[{value:"",id:""}],i=this.data.pull,s={};for(var n in i){var a=i[n][t];webix.isUndefined(a)||s[a]||(e.push({value:a,id:a}),s[a]=!0)}return e},Nu:function(){var t=this.$$("filters");t.reconstruct();for(var e=t.getChildViews(),i=this,s=0;s<e.length;s++){var n=e[s];"select"==n.name?n.attachEvent("onChange",function(t){i.Qu(this.config.field,t)
}):n.attachEvent("onTimedKeyPress",function(){i.Qu(n.config.field,n.getValue())})}},Qu:function(t,e){for(var i=this.config.structure.filters,s=0;s<i.length;s++)if(i[s].name==t)return i[s].value=e,this.render(!0),!0;return!1},Lu:function(t,e){this.Ru();var i=this.config.structure;i.I=[],i.Su={};for(var s=0;s<i.values.length;s++)i.values[s].operation=i.values[s].operation||["sum"],webix.isArray(i.values[s].operation)||(i.values[s].operation=[i.values[s].operation]);
var n=i.rows.concat(i.columns),a=this.Tu(t,e,n),r={};return i.rows.length>0?a=this.Uu(a,i.rows,i,r):(this.Vu(a,i.columns,i,r),a=[]),r=this.Wu(r),{header:r,data:a}},Tu:function(t,e,i){var s,n={};if(0===i.length)return n;for(var a=0;a<e.length;a++){var r=e[a];t[r]&&this.Xu(t[r])&&(s=t[r][i[0]],webix.isUndefined(n[s])&&(n[s]={}),n[s][r]=t[r])
}if(i.length>1)for(s in n)n[s]=this.Tu(n[s],e,i.slice(1));return n},Uu:function(t,e,i,s){var n=[];if(e.length>1){for(var a in t)t[a]=this.Uu(t[a],e.slice(1),i,s);var r=i.I;for(var a in t){for(var o={data:t[a]},h=0;h<o.data.length;h++)for(var l=0;l<r.length;l++){var c=r[l];webix.isUndefined(o[c])&&(o[c]=[]),o[c].push(o.data[h][c])
}o=this.Yu(o,i),o=this.Zu(o,i),o.name=a,o.open=!0,n.push(o)}}else for(var a in t){var o=this.Vu(t[a],this.config.structure.columns,i,s);o.name=a,o=this.Yu(o,i),o=this.Zu(o,i),n.push(o)}return n},Vu:function(t,e,i,s,n,a){if(n=n||{},e.length>0){a=a||"";for(var r in t)s[r]||(s[r]={}),t[r]=this.Vu(t[r],e.slice(1),i,s[r],n,(a.length>0?a+this.$divider:"")+r)
}else if(!webix.isUndefined(a)){var o=this.config.structure.values;for(var h in t)for(var r=0;r<o.length;r++)for(var l=0;l<o[r].operation.length;l++){var c=a+this.$divider+o[r].operation[l]+this.$divider+o[r].name;i.Su[c]||(i.I.push(c),i.Su[c]=!0),webix.isUndefined(n[c])&&(n[c]=[],s[o[r].operation[l]+this.$divider+o[r].name]={}),n[c].push(t[h][o[r].name])
}}return n},Wu:function(t){t=this.$u(t);for(var e=0;e<t.length;e++){for(var i=[],s=0;s<t[e].length;s++)i.push(t[e][s].name);t[e]={id:i.join(this.$divider),header:t[e],sort:"int",width:this.config.columnWidth}}return t.splice(0,0,{id:"name",exportAsTree:!0,template:"{common.treetable()} #name#",header:{text:webix.i18n.pivot.pivotMessage},width:this.config.yScaleWidth}),t
},$u:function(t){var e=[];for(var i in t){var s=!0;for(var n in t[i]){s=!1;break}if(s){var a=i.split(this.$divider);e.push(a.length>1?[{name:i,text:this.Ou(a[1])+" ("+this.Ku(a[0])+")"}]:[{name:i,text:i}])}else{t[i]=this.$u(t[i]);for(var r=!1,o=0;o<t[i].length;o++){var h=t[i][o];h.splice(0,0,{name:i,text:i}),r||(h[0].colspan=t[i].length,r=!0),e.push(h)
}}}return e},Yu:function(t,e){for(var i=0;i<e.I.length;i++){var s=e.I[i],n=s.split(this.$divider),a=n[n.length-2];t[s]=t[s]?this.operations[a].call(this,t[s]):"",t[s]=Math.round(1e5*t[s])/1e5}return t},Zu:function(t,e){if(!this.config.min&&!this.config.max)return t;var i=this.config.structure.values;
t.$cellCss||(t.$cellCss={});for(var s=0;s<i.length;s++){for(var n=i[s],a=[],r=-99999999,o=[],h=99999999,l=0;l<e.I.length;l++){var c=e.I[l];window.isNaN(t[c])||-1!==c.indexOf(n.name,this.length-n.name.length)&&(this.config.max&&t[c]>r?(a=[c],r=t[c]):t[c]==r&&a.push(c),this.config.min&&t[c]<h?(o=[c],h=t[c]):t[c]==h&&o.push(c))
}for(var l=0;l<o.length;l++)t.$cellCss[o[l]]="webix_min";for(var l=0;l<a.length;l++)t.$cellCss[a[l]]="webix_max"}return t},operations:{sum:function(t){for(var e=0,i=0;i<t.length;i++){var s=t[i];s=parseFloat(s,10),window.isNaN(s)||(e+=t[i])}return e},count:function(t){return t.length},max:function(t){return 1==t.length?t[0]:Math.max.apply(this,t)
},min:function(t){return 1==t.length?t[0]:Math.min.apply(this,t)}},getFields:function(){for(var t=[],e={},i=0;i<Math.min(this.data.count()||5);i++){var s=this.data.getItem(this.data.getIdByIndex(i));for(var n in s)e[n]||(t.push(n),e[n]=webix.uid())}for(var a=this.config.structure,r={fields:[],rows:[],columns:[],values:[],filters:[]},i=0;i<a.rows.length;i++){var n=a.rows[i];
webix.isUndefined(e[n])||(r.rows.push({name:n,text:this.Ou(n),id:e[n]}),delete e[n])}for(var i=0;i<a.columns.length;i++){var n=a.columns[i];webix.isUndefined(e[n])||(r.columns.push({name:n,text:this.Ou(n),id:e[n]}),delete e[n])}for(var i=0;i<a.values.length;i++){var n=a.values[i];if(!webix.isUndefined(e[n.name])){var o=this.Ou(n.name);
r.values.push({name:n.name,text:o,operation:n.operation,id:e[n.name]})}}for(var i=0;i<(a.filters||[]).length;i++){var n=a.filters[i];if(!webix.isUndefined(e[n.name])){var o=this.Ou(n.name);r.filters.push({name:n.name,text:o,type:n.type,value:n.value,id:e[n]}),delete e[n.name]}}for(var i=0;i<t.length;i++){var n=t[i];
webix.isUndefined(e[n])||r.fields.push({name:n,text:this.Ou(n),id:e[n]})}return r},Ru:function(){for(var t=this.config.structure.filters||[],e=0;e<t.length;e++){var i=t[e],s=(i.value||"").replace(/^\s+|\s+$/g,"");"="==s.substr(0,1)?(i.func=this.filters.equals,s=s.substr(1)):">="==s.substr(0,2)?(i.func=this.filters.more_equals,s=s.substr(2)):">"==s.substr(0,1)?(i.func=this.filters.more,s=s.substr(1)):"<="==s.substr(0,2)?(i.func=this.filters.less_equals,s=s.substr(2)):"<"==s.substr(0,1)?(i.func=this.filters.less,s=s.substr(1)):i.func=this.filters.contains,i.fvalue=s
}},Xu:function(t){for(var e=this.config.structure.filters||[],i=0;i<e.length;i++){var s=e[i];if(s.fvalue){if(webix.isUndefined(t[s.name]))return!1;var n=t[s.name].toString().toLowerCase(),a=s.func.call(this.filters,s.fvalue,n);if(!a)return!1}}return!0},filters:{_u:function(t,e,i){return t=window.parseFloat(t,10),e=window.parseFloat(e,10),window.isNaN(t)?!0:window.isNaN(e)?!1:i(t,e)
},contains:function(t,e){return e.indexOf(t.toString().toLowerCase())>=0},equals:function(t,e){return this._u(t,e,function(t,e){return t==e})},more:function(t,e){return this._u(t,e,function(t,e){return e>t})},more_equals:function(t,e){return this._u(t,e,function(t,e){return e>=t})},less:function(t,e){return this._u(t,e,function(t,e){return t>e
})},less_equals:function(t,e){return this._u(t,e,function(t,e){return t>=e})}},getStructure:function(){return this.config.structure},getConfigWindow:function(){return this.Ju},profile_setter:function(t){var e=window.console;t&&(this.attachEvent("onBeforeLoad",function(){e.time("data loading")}),this.data.attachEvent("onParse",function(){e.timeEnd("data loading"),e.time("data parsing")
}),this.data.attachEvent("onStoreLoad",function(){e.timeEnd("data parsing"),e.time("data processing")}),this.$ready.push(function(){this.$$("data").attachEvent("onBeforeRender",function(){this.count()&&(e.timeEnd("data processing"),e.time("data rendering"))}),this.$$("data").attachEvent("onAfterRender",function(){this.count()&&webix.delay(function(){e.timeEnd("data rendering")
})})}))}},webix.IdSpace,webix.ui.layout,webix.DataLoader,webix.EventSystem,webix.Settings),webix.protoUI({name:"webix_pivot_config",$init:function(t){this.$view.className+=" webix_popup webix_pivot",webix.extend(t,this.defaults),webix.extend(t,this.Iu(t)),this.$ready.push(this.av)},defaults:{padding:8,height:420,width:600,cancelButtonWidth:100,applyButtonWidth:100,fieldsColumnWidth:130,head:!1,modal:!0,move:!0},Iu:function(t){return{head:{view:"toolbar",cols:[{id:"config_title",data:{value:"windowMessage"},css:"webix_pivot_transparent",borderless:!0,template:this.bv.popupHeaders},{view:"button",id:"cancel",type:"iconButton",icon:"times",label:webix.i18n.pivot.cancel,width:t.cancelButtonWidth},{view:"button",id:"apply",type:"iconButton",icon:"check",css:"webix_pivot_apply",label:webix.i18n.pivot.apply,width:t.applyButtonWidth}]},body:{type:"wide",rows:[{type:"wide",margin:5,cols:[{width:t.fieldsColumnWidth,rows:[{id:"fieldsHeader",data:{value:"fields"},template:this.bv.popupHeaders,type:"header"},{id:"fields",view:"list",scroll:!1,type:{height:35},drag:!0,template:"<span class='webix_pivot_list_marker'></span>#text#",on:{onBeforeDropOut:webix.bind(this.cv,this)}}]},{view:"resizer"},{cols:[{rows:[{id:"filtersHeader",data:{value:"filters",icon:"filter"},template:this.bv.popupIconHeaders,type:"header"},{id:"filters",view:"list",scroll:!1,drag:!0,css:"webix_pivot_values",template:function(t){return t.type=t.type||"select","<a class='webix_pivot_link'>"+t.text+" <span class='webix_link_selection'>"+t.type+"</span></a> "
},type:{height:35},onClick:{webix_pivot_link:webix.bind(this.dv,this)}},{id:"rowsHeader",data:{value:"rows",icon:"th-list"},template:this.bv.popupIconHeaders,type:"header"},{id:"rows",view:"list",scroll:!1,drag:!0,template:"#text#",type:{height:35}}]},{rows:[{id:"columnsHeader",data:{value:"columns",icon:"columns"},template:this.bv.popupIconHeaders,type:"header"},{id:"columns",view:"list",scroll:!1,drag:!0,type:{height:35},template:"#text#"},{id:"valuesHeader",data:{value:"values",icon:"archive"},template:this.bv.popupIconHeaders,type:"header"},{id:"values",view:"list",scroll:!0,drag:!0,css:"webix_pivot_values",type:{height:"auto"},template:webix.bind(this.ev,this),onClick:{webix_pivot_link:webix.bind(this.fv,this),webix_pivot_plus:webix.bind(this.gv,this),webix_pivot_minus:webix.bind(this.hv,this)},on:{onBeforeDrop:webix.bind(this.iv,this),onBeforeDropOut:webix.bind(this.cv,this)}}]}]}]}]}}
},bv:{popupHeaders:function(t){return webix.i18n.pivot[t.value]},popupIconHeaders:function(t){return"<span class='webix_icon fa-"+t.icon+"'></span>"+webix.i18n.pivot[t.value]}},cv:function(t){if(t.to!=t.from){var e=t.source[0];t.from==this.$$("values")?this.$$("fields").getItem(e)&&this.$$("fields").remove(e):t.from==this.$$("fields")&&t.to!=this.$$("values")&&this.$$("values").getItem(e)&&this.$$("values").remove(e)
}},iv:function(t){if(t.to&&t.from!=t.to){var e=t.source,i=t.from.getItem(e);if(t.from==this.$$("fields"))return t.to.getItem(e)?this.gv({},e):t.to.add(webix.copy(i),t.index),!1;this.$$("fields").getItem(e)||this.$$("fields").add(webix.copy(i))}return!0},av:function(){this.attachEvent("onItemClick",function(t){"button"==this.$eventSource.name&&(this.callEvent("on"+this.innerId(t),[this.getStructure()]),this.hide())
})},ev:function(t){t.operation=t.operation||["sum"],webix.isArray(t.operation)||(t.operation=[t.operation]);for(var e=[],i=webix.$$(this.config.pivot).Ku,s=0;s<t.operation.length;s++){var n="<span class='webix_pivot_link' webix_operation='"+s+"'>";n+="<span>"+t.text+"</span>",n+="<span class='webix_link_selection'>"+i(t.operation[s])+"</span>",n+="<span class='webix_pivot_minus webix_icon fa-times'></span>",n+="</span>",e.push(n)
}return e.join(" ")},fv:function(t,e){var i={view:"webix_pivot_popup",autofit:!0,height:150,width:150,data:this.config.operations||[]},s=webix.ui(i);s.show(t),s.attachEvent("onHide",webix.bind(function(){var i=webix.html.locate(t,"webix_operation"),n=s.getSelected();null!==n&&(this.$$("values").getItem(e).operation[i]=n.name,this.$$("values").updateItem(e)),s.close()
},this))},gv:function(t,e){var i=this.$$("values").getItem(e);i.operation.push("sum"),this.$$("values").updateItem(e),webix.delay(function(){for(var t=i.operation.length-1,s=this.$$("values").getItemNode(e).childNodes,n=null,a=0;a<s.length;a++)if(n=s[a],n.getAttribute){var r=n.getAttribute("webix_operation");
if(!webix.isUndefined(r)&&r==t)break}null!==n&&this.fv(n,e)},this)},hv:function(t,e){var i=webix.html.locate(t,"webix_operation"),s=this.$$("values").getItem(e);return s.operation.length>1?(s.operation.splice(i,1),this.$$("values").updateItem(e)):this.$$("values").remove(e),!1},dv:function(t,e){var i=webix.$$(this.config.pivot).Ku,s={view:"webix_pivot_popup",autofit:!0,height:150,width:150,data:[{name:"select",title:i("select")},{name:"text",title:i("text")}]},n=webix.ui(s);
n.show(t),n.attachEvent("onHide",webix.bind(function(){var t=n.getSelected();if(null!==t){var i=this.$$("filters").getItem(e);i.type=t.name,this.$$("filters").updateItem(e)}n.close()},this))},data_setter:function(t){this.$$("fields").clearAll(),this.$$("fields").parse(t.fields),this.$$("filters").clearAll(),this.$$("filters").parse(t.filters),this.$$("columns").clearAll(),this.$$("columns").parse(t.columns),this.$$("rows").clearAll(),this.$$("rows").parse(t.rows),this.$$("values").clearAll(),this.$$("values").parse(t.values)
},setStructure:function(t){this.define("structure",t),this.render()},getStructure:function(){var t={rows:[],columns:[],values:[],filters:[]},e=this.$$("rows");e.data.each(function(e){t.rows.push(e.name)});var i=this.$$("columns");i.data.each(function(e){t.columns.push(e.name)});var s=this.$$("values");
s.data.each(function(e){t.values.push(e)});var n=this.$$("filters");return n.data.each(function(e){t.filters.push(e)}),t}},webix.ui.window,webix.IdSpace),webix.protoUI({name:"webix_pivot_popup",wg:null,$init:function(t){webix.extend(t,this.Iu(t)),this.$ready.push(this.av)},Iu:function(t){return{body:{id:"list",view:"list",scroll:!1,borderless:!0,autoheight:!0,template:"#title#",data:t.data}}
},av:function(){this.attachEvent("onItemClick",function(t){this.wg=this.$eventSource.getItem(t),this.hide()})},getSelected:function(){return this.wg}},webix.ui.popup,webix.IdSpace),webix.i18n.pivot=webix.extend(webix.i18n.pivot||{},{apply:"Apply",bar:"Bar",cancel:"Cancel",groupBy:"Group By",chartType:"Chart type",count:"count",fields:"Fields",filters:"Filters",line:"Line",max:"max",min:"min",operationNotDefined:"Operation is not defined",layoutIncorrect:"pivotLayout should be an Array instance",pivotMessage:"Click to configure",popupHeader:"Pivot Settings",radar:"Radar",radarArea:"Area Radar",select:"select",settings:"Settings",stackedBar:"Stacked Bar",sum:"sum",text:"text",values:"Values",valuesNotDefined:"Values or Group field are not defined",windowMessage:"[move fields into required sector]"}),webix.protoUI({name:"pivot-chart",version:"2.1.0",defaults:{fieldMap:{},rows:[],filterLabelAlign:"right",filterWidth:300,filterMinWidth:150,editButtonWidth:110,filterLabelWidth:100,chartType:"bar",color:"#36abee",chart:{},singleLegendItem:1,palette:[["#e33fc7","#a244ea","#476cee","#36abee","#58dccd","#a7ee70"],["#d3ee36","#eed236","#ee9336","#ee4339","#595959","#b85981"],["#c670b8","#9984ce","#b9b9e2","#b0cdfa","#a0e4eb","#7faf1b"],["#b4d9a4","#f2f79a","#ffaa7d","#d6806f","#939393","#d9b0d1"],["#780e3b","#684da9","#242464","#205793","#5199a4","#065c27"],["#54b15a","#ecf125","#c65000","#990001","#363636","#800f3e"]]},templates:{groupNameToStr:function(t,e){return t+"_"+e
},groupNameToObject:function(t){var e=t.split("_");return{name:e[0],operation:e[1]}},seriesTitle:function(t,e){var i=this.config.fieldMap[t.name]||t.name,s=webix.isArray(t.operation)?t.operation[e]:t.operation;return i+" ( "+(webix.i18n.pivot[s]||s)+")"}},templates_setter:function(t){"object"==typeof t&&webix.extend(this.templates,t)
},chartMap:{bar:function(t){return{border:0,alpha:1,radius:0,color:t}},line:function(t){return{alpha:1,item:{borderColor:t,color:t},line:{color:t,width:2}}},radar:function(t){return{alpha:1,fill:!1,disableItems:!0,item:{borderColor:t,color:t},line:{color:t,width:2}}}},chartMap_setter:function(t){"object"==typeof t&&webix.extend(this.chartMap,t,!0)
},$init:function(t){t.structure||(t.structure={}),webix.extend(t.structure,{groupBy:"",values:[],filters:[]}),this.$view.className+=" webix_pivot_chart",webix.extend(t,{editButtonWidth:this.defaults.editButtonWidth}),webix.extend(t,this.getUI(t)),this.$ready.push(this.render),this.data.attachEvent("onStoreUpdated",webix.bind(function(){this.$$("chart")&&this.render(this,arguments)
},this))},getUI:function(t){var e={view:"toolbar",id:"toolbar",cols:[{id:"filters",hidden:!0,cols:[]},{id:"edit",view:"button",type:"iconButton",align:"right",icon:"cog",inputWidth:t.editButtonWidth,label:this.jv("settings"),click:webix.bind(this.configure,this)},{width:5}]},i={id:"bodyLayout",type:"line",margin:10,cols:[{id:"chart",view:"chart"}]};
return{rows:[e,i]}},configure:function(){if(!this.pivotPopup){var t={id:"popup",view:"webix_pivot_chart_config",operations:[],pivot:this.config.id};webix.extend(t,this.config.popup||{}),this.pivotPopup=webix.ui(t),this.pivotPopup.attachEvent("onApply",webix.bind(function(t){this.config.chartType=this.pivotPopup.$$("chartType")?this.pivotPopup.$$("chartType").getValue():"bar",this.config.chart.scale=this.pivotPopup.$$("logScale").getValue()?"logarithmic":"linear",webix.extend(this.config.structure,t,!0),this.render()
},this))}var e=[];for(var i in this.operations)e.push({name:i,title:this.jv(i)});this.pivotPopup.kv=this.kv,this.pivotPopup.define("operations",e);var s=webix.html.offset(this.$$("chart").getNode());this.pivotPopup.setPosition(s.x+10,s.y+10),this.pivotPopup.define("data",this.getFields()),this.pivotPopup.lv=this.pivotPopup.show()
},render:function(){var t=this.mv();t.length?(t.push({}),this.$$("filters").show(),this.$$("filters").define("cols",t),this.nv()):this.$$("filters").hide(),this.ov(),this.pv(),this.qv()},pv:function(){for(var t=this.config,e=t.structure.values,i=0;i<e.length;i++)e[i].operation=e[i].operation||["sum"],webix.isArray(e[i].operation)||(e[i].operation=[e[i].operation]);
var s=this.config.chartType||"bar",n=this.chartMap[s],a={type:n&&n("").type?n("").type:s,xAxis:webix.extend({template:"#id#"},t.chart.xAxis||{}),yAxis:webix.extend({},t.chart.yAxis||{})};webix.extend(a,t.chart);var r=this.rv();a.series=r.series,a.legend=!1,(t.singleLegendItem||this.kv>1)&&(a.legend=r.legend),a.scheme={$group:this.sv,$sort:{by:"id"}},this.$$("chart").removeAllSeries();
for(var o in a)this.$$("chart").define(o,a[o])},jv:function(t){return webix.i18n.pivot[t]||t},tv:function(t){return this.config.fieldMap[t]||t},mv:function(){for(var t=this.config.structure.filters||[],e=[],i=0;i<t.length;i++){var s=t[i],n={value:s.value,label:this.tv(s.name),field:s.name,view:s.type,labelAlign:this.config.filterLabelAlign,labelWidth:this.config.filterLabelWidth,minWidth:this.config.filterMinWidth,maxWidth:this.config.filterWidth};
"select"==s.type&&(n.options=this.uv(s.name)),e.push(n)}return e},uv:function(t){var e=[{value:"",id:""}],i=this.data.pull,s={};for(var n in i){var a=i[n][t];webix.isUndefined(a)||s[a]||(e.push({value:a,id:a}),s[a]=!0)}return e.sort(function(t,e){var i=t.value,s=e.value;return s?i?(i=i.toString().toLowerCase(),s=s.toString().toLowerCase(),i>s?1:s>i?-1:0):-1:1
}),e},qv:function(){this.ov(),this.data.silent(function(){this.data.filter(webix.bind(this.vv,this))},this),this.$$("chart").data.silent(function(){this.$$("chart").clearAll()},this),this.$$("chart").parse(this.data.getRange())},nv:function(){var t=this.$$("filters");t.reconstruct();for(var e=t.getChildViews(),i=this,s=0;s<e.length;s++){var n=e[s];
"select"==n.name?n.attachEvent("onChange",function(t){i.wv(this.config.field,t)}):webix.isUndefined(n.getValue)||n.attachEvent("onTimedKeyPress",function(){i.wv(this.config.field,this.getValue())})}},wv:function(t,e){for(var i=this.config.structure.filters,s=0;s<i.length;s++)if(i[s].name==t)return i[s].value=e,this.qv(),!0;
return!1},groupNameToStr:function(t){return t.name+"_"+t.operation},groupNameToObject:function(t){var e=t.split("_");return{name:e[0],operation:e[1]}},rv:function(){var t,e,i,s,n,a={},r=[],o=this.config.structure.values;for(i={valign:"middle",align:"right",width:140,layout:"y"},webix.extend(i,this.config.chart.legend||{},!0),i.values=[],i.marker||(i.marker={}),i.marker.type="line"==this.config.chartType?"item":"s",this.series_names=[],this.kv=0,t=0;t<o.length;t++)for(webix.isArray(o[t].operation)||(o[t].operation=[o[t].operation]),webix.isArray(o[t].color)||(o[t].color=[o[t].color||this.xv(this.kv)]),e=0;e<o[t].operation.length;e++){s=this.templates.groupNameToStr(o[t].name,o[t].operation[e]),this.series_names.push(s),o[t].color[e]||(o[t].color[e]=this.xv(this.kv));
var h=o[t].color[e],l=this.chartMap[this.config.chartType](h)||{};l.value="#"+s+"#",l.tooltip={template:webix.bind(function(t){return t[this].toFixed(3)},s)},r.push(l),n=this.templates.seriesTitle.call(this,o[t],e),i.values.push({text:n,color:h}),a[s]=[o[t].name,o[t].operation[e]],this.kv++}return this.sv={},o.length&&(this.sv=webix.copy({by:this.config.structure.groupBy,map:a})),{series:r,legend:i}
},xv:function(t){var e=this.config.palette,i=t/e[0].length;i=i>e.length?0:parseInt(i,10);var s=t%e[0].length;return e[i][s]},yv:function(){var t,e,i,s=this.config.structure.values;for(e={valign:"middle",align:"right",width:140,layout:"y"},webix.extend(e,this.config.chart.legend||{},!0),e.values=[],e.marker||(e.marker={}),e.marker.type="line"==this.config.chartType?"item":"s",t=0;t<s.length;t++)i=this.templates.seriesTitle.call(this,s[t]),e.values.push({text:i,color:s[t].color});
return e},operations:{sum:1,count:1,max:1,min:1},addGroupMethod:function(t,e){this.operations[t]=1,e&&(webix.GroupMethods[t]=e)},removeGroupMethod:function(t){delete this.operations[t]},groupMethods_setter:function(t){for(var e in t)t.hasOwnProperty(e)&&this.addGroupMethod(e,t[e])},getFields:function(){var t,e=[],i={};
for(t=0;t<Math.min(this.data.count()||5);t++){var s=this.data.getItem(this.data.getIdByIndex(t));for(var n in s)i[n]||(e.push(n),i[n]=webix.uid())}var a=this.config.structure,r={fields:[],groupBy:[],values:[],filters:[]},o="object"==typeof a.groupBy?a.groupBy[0]:a.groupBy;webix.isUndefined(i[o])||(r.groupBy.push({name:o,text:this.tv(o),id:i[o]}),delete i[o]);
var h={};for(t=0;t<a.values.length;t++){var o=a.values[t];if(!webix.isUndefined(i[o.name])){var l=this.tv(o.name);if(webix.isUndefined(h[o.name]))h[o.name]=r.values.length,r.values.push({name:o.name,text:l,operation:o.operation,color:o.color||[this.xv(t)],id:i[o.name]});else{var c=r.values[h[o.name]];
c.operation=c.operation.concat(o.operation),c.color=c.color.concat(o.color||[this.xv(t)])}}}for(t=0;t<(a.filters||[]).length;t++){var o=a.filters[t];if(!webix.isUndefined(i[o.name])){var l=this.tv(o.name);r.filters.push({name:o.name,text:l,type:o.type,value:o.value,id:i[o]}),delete i[o.name]}}for(t=0;t<e.length;t++){var o=e[t];
webix.isUndefined(i[o])||r.fields.push({name:o,text:this.tv(o),id:i[o]})}return r},ov:function(){for(var t=this.config.structure.filters||[],e=0;e<t.length;e++){var i=t[e],s=(i.value||"").trim();"="==s.substr(0,1)?(i.func=this.filters.equals,s=s.substr(1)):">="==s.substr(0,2)?(i.func=this.filters.more_equals,s=s.substr(2)):">"==s.substr(0,1)?(i.func=this.filters.more,s=s.substr(1)):"<="==s.substr(0,2)?(i.func=this.filters.less_equals,s=s.substr(2)):"<"==s.substr(0,1)?(i.func=this.filters.less,s=s.substr(1)):s.indexOf("...")>0?(i.func=this.filters.range,s=s.split("...")):s.indexOf("..")>0?(i.func=this.filters.range_inc,s=s.split("..")):i.func=this.filters.contains,i.fvalue=s
}},vv:function(t){for(var e=this.config.structure.filters||[],i=0;i<e.length;i++){var s=e[i];if(s.fvalue){if(webix.isUndefined(t[s.name]))return!1;var n=t[s.name].toString().toLowerCase(),a=s.func.call(this.filters,s.fvalue,n);if(!a)return!1}}return!0},filters:{_u:function(t,e,i){if("object"==typeof t){for(var s=0;s<t.length;s++)if(t[s]=window.parseFloat(t[s],10),window.isNaN(t[s]))return!0
}else if(t=window.parseFloat(t,10),window.isNaN(t))return!0;return window.isNaN(e)?!1:i(t,e)},contains:function(t,e){return e.indexOf(t.toString().toLowerCase())>=0},equals:function(t,e){return this._u(t,e,function(t,e){return t==e})},more:function(t,e){return this._u(t,e,function(t,e){return e>t})},more_equals:function(t,e){return this._u(t,e,function(t,e){return e>=t
})},less:function(t,e){return this._u(t,e,function(t,e){return t>e})},less_equals:function(t,e){return this._u(t,e,function(t,e){return t>=e})},range:function(t,e){return this._u(t,e,function(t,e){return e<t[1]&&e>=t[0]})},range_inc:function(t,e){return this._u(t,e,function(t,e){return e<=t[1]&&e>=t[0]
})}},getStructure:function(){return this.config.structure},getConfigWindow:function(){return this.Ju}},webix.IdSpace,webix.ui.layout,webix.DataLoader,webix.EventSystem,webix.Settings),webix.protoUI({name:"webix_pivot_chart_config",$init:function(t){this.$view.className+=" webix_pivot_chart_popup",webix.extend(t,this.defaults),webix.extend(t,this.zv(t)),this.$ready.push(this.Av)
},defaults:{padding:8,height:600,width:600,head:!1,modal:!0,move:!0,chartTypeLabelWidth:80,chartTypeWidth:250,cancelButtonWidth:100,applyButtonWidth:100,fieldsColumnWidth:250},zv:function(t){var e=[],i=webix.$$(t.pivot),s=i.chartMap;for(var n in s)e.push({id:n,value:i.jv(n)});return{head:{view:"toolbar",cols:[{id:"config_title",data:{value:"windowMessage"},css:"webix_pivot_transparent",borderless:!0,template:this.bv.popupHeaders},{view:"button",id:"cancel",type:"iconButton",icon:"times",label:i.jv("cancel"),width:t.cancelButtonWidth},{view:"button",id:"apply",type:"iconButton",icon:"check",css:"webix_pivot_apply",label:i.jv("apply"),width:t.applyButtonWidth}]},body:{rows:[{type:"wide",margin:5,cols:[{width:t.fieldsColumnWidth,rows:[{id:"config_title",data:{value:"fields"},template:this.bv.popupHeaders,type:"header"},{id:"fields",view:"list",scroll:!1,type:{height:35},drag:!0,template:"<span class='webix_pivot_list_marker'></span>#text#",on:{onBeforeDrop:webix.bind(this.Bv,this),onBeforeDropOut:webix.bind(this.Cv,this),onBeforeDrag:webix.bind(this.Dv,this)}}]},{view:"resizer"},{rows:[{id:"filtersHeader",data:{value:"filters",icon:"filter"},template:this.bv.popupIconHeaders,type:"header"},{id:"filters",view:"list",scroll:!0,gravity:2,drag:!0,css:"webix_pivot_values",template:function(t){return t.type=t.type||"select","<div class='webix_pivot_link'>"+t.text+"<div class='webix_link_selection filter'>"+t.type+"</div></div> "
},type:{height:35},onClick:{webix_link_selection:webix.bind(this.Ev,this)},on:{onBeforeDrag:webix.bind(this.Dv,this)}},{id:"valuesHeader",data:{value:"values",icon:"archive"},template:this.bv.popupIconHeaders,type:"header"},{id:"values",view:"list",scroll:!0,gravity:3,drag:!0,css:"webix_pivot_values",type:{height:"auto"},template:webix.bind(this.ev,this),onClick:{webix_link_title:webix.bind(this.fv,this),webix_link_selection:webix.bind(this.fv,this),webix_color_selection:webix.bind(this.Fv,this),webix_pivot_minus:webix.bind(this.hv,this)},on:{onBeforeDrop:webix.bind(this.Gv,this),onBeforeDropOut:webix.bind(this.Cv,this),onBeforeDrag:webix.bind(this.Dv,this)}},{id:"groupHeader",data:{value:"groupBy",icon:"tags"},template:this.bv.popupIconHeaders,type:"header"},{id:"groupBy",view:"list",yCount:1,scroll:!1,drag:!0,type:{height:35},template:"<a class='webix_pivot_link'>#text#</a> ",on:{onBeforeDrop:webix.bind(this.Hv,this),onBeforeDrag:webix.bind(this.Dv,this)}}]}]},{borderless:!0,padding:5,type:"space",cols:[{view:"checkbox",id:"logScale",value:i.config.chart.scale&&"logarithmic"==i.config.chart.scale,label:webix.i18n.pivot.logScale,labelWidth:t.logScaleLabelWidth,width:t.logScaleLabelWidth+20},{},{view:"richselect",id:"chartType",value:i.config.chartType,label:webix.i18n.pivot.chartType,options:e,labelWidth:t.chartTypeLabelWidth,width:t.chartTypeWidth}]}]}}
},bv:{popupHeaders:function(t){return webix.i18n.pivot[t.value]},popupIconHeaders:function(t){return"<span class='webix_icon fa-"+t.icon+"'></span>"+webix.i18n.pivot[t.value]}},Dv:function(){webix.callEvent("onClick",[])},Bv:function(t){if(t.from==this.$$("values")){var e=t.source[0];return this.$$("values").getItem(e)&&this.$$("values").remove(e),!1
}return!0},Cv:function(t){if(t.to!=t.from){var e=t.source[0];t.from==this.$$("values")&&t.to!=this.$$("fields")?(delete this.$$("values").getItem(e).operation,delete this.$$("values").getItem(e).color,this.$$("fields").getItem(e)&&this.$$("fields").remove(e)):t.from==this.$$("fields")&&t.to!=this.$$("values")&&this.$$("values").getItem(e)&&this.$$("values").remove(e)
}},Gv:function(t){if(t.to&&t.from!=t.to){var e=t.source,i=t.from.getItem(e);if(t.from==this.$$("fields"))return t.to.getItem(e)?(this.gv({},e),this.kv++):(i=webix.copy(i),t.to.add(webix.copy(i),t.index),this.kv++),!1;this.$$("fields").getItem(e)||this.$$("fields").add(webix.copy(i)),this.Iv=!0}return!0
},Hv:function(){if(this.$$("groupBy").data.order.length){var t=this.$$("groupBy").getFirstId(),e=webix.copy(this.$$("groupBy").getItem(t));this.$$("groupBy").remove(t),this.$$("fields").add(e)}return!0},Av:function(){this.attachEvent("onItemClick",function(t){if("button"==this.$eventSource.name){var e=this.getStructure();
"apply"!=this.innerId(t)||e.values.length&&e.groupBy?(this.callEvent("on"+this.innerId(t),[e]),this.hide()):webix.alert(webix.i18n.pivot.valuesNotDefined)}})},ev:function(t){t.operation=t.operation||["sum"],webix.isArray(t.operation)||(t.operation=[t.operation]);for(var e=[],i=webix.$$(this.config.pivot),s=i.jv,n=0;n<t.operation.length;n++){t.color||(t.color=[i.xv(this.kv)]),t.color[n]||t.color.push(i.xv(this.kv));
var a="<div class='webix_pivot_link' webix_operation='"+n+"'>";a+="<div class='webix_color_selection'><div style='background-color:"+s(t.color[n])+"'></div></div>",a+="<div class='webix_link_title'>"+t.text+"</div>",a+="<div class='webix_link_selection'>"+s(t.operation[n])+"</div>",a+="<div class='webix_pivot_minus webix_icon fa-times'></div>",a+="</div>",e.push(a)
}return this.Iv&&(this.Iv=!1,this.kv++),e.join(" ")},fv:function(t,e){var i={view:"webix_pivot_chart_popup",autofit:!0,autoheight:!0,width:150,data:this.config.operations||[]},s=webix.ui(i);s.show(t),s.attachEvent("onHide",webix.bind(function(){var i=webix.html.locate(t,"webix_operation"),n=s.getSelected();
null!==n&&(this.$$("values").getItem(e).operation[i]=n.name,this.$$("values").updateItem(e)),s.close()},this))},Fv:function(t,e){var i={view:"colorboard",id:"colorboard",borderless:!0};webix.$$(this.config.pivot).config.colorboard?webix.extend(i,webix.$$(this.config.pivot).config.colorboard):webix.extend(i,{width:150,height:150,palette:webix.$$(this.config.pivot).config.palette});
var s=webix.ui({view:"popup",id:"colorsPopup",body:i});return s.show(t),s.getBody().attachEvent("onSelect",function(){s.hide()}),s.attachEvent("onHide",webix.bind(function(){var i=webix.html.locate(t,"webix_operation"),n=s.getBody().getValue();n&&(this.$$("values").getItem(e).color[i]=n,this.$$("values").updateItem(e)),s.close()
},this)),!1},gv:function(t,e){var i=this.$$("values").getItem(e);i.operation.push("sum");{var s=webix.$$(this.config.pivot);s.config.palette}i.color.push(s.xv(this.kv)),this.$$("values").updateItem(e),webix.delay(function(){for(var t=i.operation.length-1,s=this.$$("values").getItemNode(e).childNodes,n=null,a=0;a<s.length;a++)if(n=s[a],n.getAttribute){var r=n.getAttribute("webix_operation");
if(!webix.isUndefined(r)&&r==t)break}null!==n&&this.fv(n,e)},this)},hv:function(t,e){var i=webix.html.locate(t,"webix_operation"),s=this.$$("values").getItem(e);return s.operation.length>1?(s.operation.splice(i,1),this.$$("values").updateItem(e)):this.$$("values").remove(e),!1},Ev:function(t,e){var i=webix.$$(this.config.pivot).jv,s={view:"webix_pivot_chart_popup",autofit:!0,height:150,width:150,data:[{name:"select",title:i("select")},{name:"text",title:i("text")}]},n=webix.ui(s);
n.show(t),n.attachEvent("onHide",webix.bind(function(){var t=n.getSelected();if(null!==t){var i=this.$$("filters").getItem(e);i.type=t.name,this.$$("filters").updateItem(e)}n.close()},this))},data_setter:function(t){this.$$("fields").clearAll(),this.$$("fields").parse(t.fields),this.$$("filters").clearAll(),this.$$("filters").parse(t.filters),this.$$("groupBy").clearAll(),this.$$("groupBy").parse(t.groupBy),this.$$("values").clearAll(),this.$$("values").parse(t.values)
},getStructure:function(){var t={groupBy:"",values:[],filters:[]},e=this.$$("groupBy");e.count()&&(t.groupBy=e.getItem(e.getFirstId()).name);var i,s=this.$$("values");s.data.each(webix.bind(function(e){for(var s=0;s<e.operation.length;s++)i=webix.copy(e),webix.extend(i,{operation:e.operation[s],color:e.color[s]||webix.$$(this.config.pivot).config.color},!0),t.values.push(i)
},this));var n=this.$$("filters");return n.data.each(function(e){t.filters.push(e)}),t}},webix.ui.window,webix.IdSpace),webix.protoUI({name:"webix_pivot_chart_popup",wg:null,$init:function(t){webix.extend(t,this.Iu(t)),this.$ready.push(this.av)},Iu:function(t){return{body:{id:"list",view:"list",borderless:!0,autoheight:!0,template:"#title#",data:t.data}}
},av:function(){this.attachEvent("onItemClick",function(t){this.wg=this.$eventSource.getItem(t),this.hide()})},getSelected:function(){return this.wg}},webix.ui.popup,webix.IdSpace);
//# sourceMappingURL=./pivot.js.map