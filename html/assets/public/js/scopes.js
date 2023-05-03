//import Vue from "vue";

export default{
    getData:function(info){
        //this.data.push(info);
        Array.prototype.push.apply(this.data, info);
        return this.data;
    },
    data: []

}

