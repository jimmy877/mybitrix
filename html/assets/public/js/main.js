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
        "component-2":Component2,
        "slider": Slider,
    },
});
mainPage.mount('#app');
