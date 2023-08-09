export default  {
        data() {
            return {
                count: 0,
                test:"hii",
            }
        },
        template: `
        <button @click="count++">
          Счётчик кликов — {{ count }}
        </button>
        <div>Привеееет</div>
        <div>{{test}}</div>
        `
}
