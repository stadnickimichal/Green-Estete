document.addEventListener("DOMContentLoaded", function(){
    class addingData{
        constructor(){
            this.td=$('td');
            this.input=$('input');
            this.select=$('select');
            this.mouseCourse;
            this.previouseMouseY=0;
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
                if ($(element).closest("table").attr('id')===day){
                    $(element).bind('click',()=>this.function2(element));
                    $(element).bind('mouseout',()=>this.function3(element));
                    $(element).bind('mousemove',()=>this.mouseMove(event));
                };
            });
        }
        function2(element){
            let tdIndex= element.id.split("-")[1];
            let min= (tdIndex%4)*15;
            let h= Math.floor(tdIndex/4)+6;
            $(element).css("background-color",'rgb(0, 128, 0)');
            this.input[2].value= h;
            this.select[2].value= min;
            this.td.each((index,element)=>{
                $(element).unbind();
            });
        }
        function3(element){
            if(this.mouseCourse){
                $(element).css("background-color",'rgb(0, 128, 0)');
                $(element).css("border",'0');
            }
            else{
                $(element).css("background-color",'rgba(0, 0, 0, 0)');
                $(element).css("border",'1px solid #000000');
            }
        }
        mouseMove(event){
            if(this.previouseMouseY<event.pageY){
                this.mouseCourse=true;
            }
            else{
                this.mouseCourse=false;
            }
            this.previouseMouseY=event.pageY;
            console.log(this.mouseCourse);
        }
    }
    let obj1 = new addingData();
    obj1.begin();
});