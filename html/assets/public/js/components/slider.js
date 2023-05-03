import scopes from "../scopes.js";
export default {
    data(){
        return{
            text:"Слайдер",
            slides:[],
        }
    },
    props:[],
    template:`
        <div>
              <div v-for="item in slides" :index="item.id" @click="touch">
                    <div class="h-title">{{ item.title }}</div>
                    <div class="h-sub-title">{{ item.subTitle }}</div>
                    <img :src="item.img">
                 
              </div>
        </div>
    `,
    methods:{
        touch(e){
            //this.text = "Название изменилось";
            //event.target.attributes.index = "hshshs"
            console.log(e );
        }
    },
    created(){
        this.slides = scopes.getData(data);
    }
}