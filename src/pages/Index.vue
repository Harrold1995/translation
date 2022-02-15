<template>
    <q-layout view="hHh lpR fff" >
    
      <component :is="dynamicComponent"></component>
      
    </q-layout>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import test from 'components/test/test.vue'


export default {
  // name: 'AboutUs',
  data () {
    return {
      dynamicComponent: {}
    }
  },
  computed: {
    ...mapState({
      templateData: state => state.tester.hello
    })
  },
  methods: {
    ...mapActions('tester', ['helloWorld']),
    templateFn () {
        this.helloWorld()
          .then(response => {
            // console.log(response)
            this.dynamicComponent = {
               components: {
                  // eslint-disable-next-line vue/no-unused-components
                  test
                },
              data () {
                return {
                }
              },
              template: response,
            };
          })
          .catch(e => {
            const responseMessage = e.data
          })
    }
  },
  created() {
      this.templateFn()
  },
}
</script>