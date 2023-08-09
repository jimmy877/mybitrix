import scopes from "../scopes.js";
import {VueSlickCarousel}  from './slick.js';
export default {
    data(){
        return{
            text:"Слайдер",
            slides:[],
        }
    },
    props:[],
    components:{
        //"VueSlickCarousel":httpVueLoader('vue-slick-carousel'),
        VueSlickCarousel ,

    },
    template:`
        <div>
            <slick :arrows="true" :dots="true" ref="slick">

            </slick>
            <div v-for="item in slides" :index="item.id" @click="touch" :class="'slides slide-'+item.id">
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
            console.log(e);
        }
    },
    created(){
        this.slides = scopes.getData(data);
    },
    mounted(){
        let slides = document.querySelectorAll('.slides');
        slides[0].classList.add("active");
    }
}