<template>
    <q-layout view="hHh lpR fff">
      
      <header id="masthead" class="site-header">
        <Header/>
      </header>

      <!-- #masthead -->
      <div class="clearfix"></div>
      <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
          <section class="sec-row sec-content landingpage" style="padding: 60px 0">
            <LanguageTiles/>
            <div class="clearfix">
            </div>
          </section>
        </main>
      </div>

      <div class="clearfix"></div>
      <footer id="colophon" class="site-footer" >
        <FooterMid/>
        <FooterBottom/>
      </footer>
  
    </q-layout>
</template>

<script>
// import $ from 'jquery'
import { mapState, mapActions } from 'vuex'
import Header from 'components/static/header/MainHeader.vue'
import LanguageTiles from 'components/static/content/main/LanguageTiles.vue'
import FooterMid from 'components/static/footer/FooterMid.vue'
import FooterBottom from 'components/static/footer/FooterBottom.vue'


export default {
  // name: 'AboutUs',
  components: {
    Header,
    LanguageTiles,
    FooterMid,
    FooterBottom,
  },
  data () {
    return {
      sample: ''
    }
  },
  computed: {
    ...mapState({
      hello: state => state.tester.hello
    })
  },
  methods: {
    ...mapActions('tester', ['helloWorld']),
    testBtn () {
        this.helloWorld()
          .then(response => {
            console.log(response)
          })
          .catch(e => {
            const responseMessage = e.data
            // console.log(responseMessage)
          })
    }
  },
  mounted() {
    this.testBtn();
  }
}
</script>