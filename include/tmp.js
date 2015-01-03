data = [
        { id:"1", open:true,tsect:"1", value:"The Shawshank Redemption", data:[
            { id:"1.1", value:"Part 1",tsect:"1" },
            { id:"1.2", value:"Part 2",tsect:"1" },
            { id:"1.3", value:"Part 3",tsect:"1" }
        ]},
        { id:"2", value:"The Godfather", tsect:"0", data:[
            { id:"2.1", value:"Part 1",tsect:"0"},
            { id:"2.2", value:"Part 2",tsect:"0" }
                ]}
    ];

maintable = {
     rows: [
        {view:"tabbar", id:"chPage", 
         click: function(){
                            var val = "" + this.getValue().split("_")[1];
                            $$("list_itbase").filter(function(obj){
                                 return ( obj == undefined) ? false : obj.tsect == val;
                            })}, 
         value: "sect_0",
         options: [ 
                { value: "A", id:"sect_0",width:50 },
                { value: "B", id:"sect_1",width:50 },
                   ],
         minWidth:400, 
        },
        {
            cols:[
                { rows:[ITBasePage] },

            ]
        }
    ]
};