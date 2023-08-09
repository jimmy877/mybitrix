import ButtonCounter from "./components/ButtonCounter.js";
import Component2 from "./components/component2.js";
import Slider from  "./components/slider.js";
const mainPage = Vue.createApp({
    data() {
        return {
            counter: 0,
            test2:"toеще текст",
        }
    },
    mounted() {
        setInterval(() => {
            this.counter++
        }, 1000)
    },
    components:{
        //"button-counter":ButtonCounter,
       // "component-2":Component2,
       //"VueSlickCarousel": VueSlickCarousel,
        //"VueSlickCarousel":httpVueLoader('./components/slick2/VueSlickCarousel.vue'),
        "slider": Slider,
        //"slider-component":httpVueLoader('/components/SliderComponent.vue'),
    },
});
//mainPage.component("slider-component", httpVueLoader('/components/SliderComponent.vue'));
mainPage.mount('#app');
