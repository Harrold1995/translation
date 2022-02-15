<template>
    <q-layout view="hHh lpR fff">

       <component :is="dynamicComponent"></component>
      
    </q-layout>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import FatPlugin from 'components/plugins/FatPlugin.vue'
import AdsCarousel from 'components/plugins/AdsCarousel.vue'
import Ads1 from 'components/plugins/Ads1.vue'
import Ads2 from 'components/plugins/Ads2.vue'
import Ads3 from 'components/plugins/Ads3.vue'
import FooterCarousel from 'components/plugins/FooterCarousel.vue'



export default {
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
    ...mapActions('tester', ['loadPage']),
    templateFn () {
        this.loadPage(this.$route.params.page)
          .then(response => {
            console.log(response)
            this.dynamicComponent = {
              components: {
                  // eslint-disable-next-line vue/no-unused-components
                  FooterCarousel,
                  AdsCarousel,
                  Ads1,
                  Ads2,
                  Ads3,
                  FatPlugin
                },
              data () {
                return {
                  slide: 1,
                }
              },
              template: response,
            }
          })
          .catch(e => {
            const responseMessage = e.data
            console.log(responseMessage)
             this.$router.push({ path: '/error'})
          })
    }
  },
  created() {
    this.templateFn();
  },
  watch: {
    '$route.params.page': function () {
      this.templateFn()
    },
  }
}
</script>