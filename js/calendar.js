document.addEventListener("DOMContentLoaded", function(){
    class addingData{
        constructor(){
            this.td=$('td');
            this.input=$('input');
            this.select=$('select');
        }

        begin(){
            this.td.each((index,element)=>{
                $(element).bind('click',()=>this.function1(element));
            });
        }
        function1(element){
            let day= element.id.split("-")[0];
            let tdIndex= element.id.split("-")[1];
            let min= (tdIndex%4)*15;
            let h= Math.floor(tdIndex/4)+6;
            this.select[0].value= day;
            this.input[1].value= h;
            this.select[1].value= min;
            this.td.each((index,element)=>{
                $(element).unbind();
                if ($(element).closest("table").attr('id')==day){
                    $(element).bind('click',()=>this.function2(element));
                    $(element).bind('mouseout',()=>this.function3(element));
                };
            });
        }
        function2(element){
            let tdIndex= element.id.split("-")[1];
            let min= (tdIndex%4)*15;
            let h= Math.floor(tdIndex/4)+6;
            this.input[2].value= h;
            this.select[2].value= min;
            this.td.each((index,element)=>{
                $(element).unbind();
            });
        }
        function3(element){
            console.log($(element).css("background-color"));
            console.log(($(element).css("background-color")=='rgba(0, 0, 0, 0)')?'rgb(0, 128, 0)':'rgba(0, 0, 0, 0)');
            $(element).css("background-color", ($(element).css("background-color")=='rgba(0, 0, 0, 0)')?'rgb(0, 128, 0)':'rgba(0, 0, 0, 0)');
        }
    }
    let obj1 = new addingData();
    obj1.begin();
});