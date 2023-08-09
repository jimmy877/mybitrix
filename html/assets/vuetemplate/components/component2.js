import scopes from "../scopes.js";
export default {
    data(){
        return{
            text:"Компонент 2",
            info:[],
        }
    },
    template:`
        <p>Название компонента 2</p>
        <p @click="touch">{{text}}</p>
        <div style="background-color: #FFF">
            <h1>Тестируем данные</h1>
            <p>
            {{info}}
                <span v-for="item in info" :index="item.id" @click="touch">
                    {{ item.name }}
                </span>
            </p>
        </div>
    `,
    methods:{
        touch(){
            this.text = "Название изменилось";
        }
    },
    created(){
        //this.info = scopes.info;
        scopes.getData(testData);
        console.log(scopes);
    }

}