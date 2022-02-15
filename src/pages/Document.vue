<template>
    <q-layout view="hHh lpR fff">
      
      <header id="masthead" class="site-header">
        <Header/>
      </header>

      <!-- #masthead -->
      <div class="clearfix"></div>
      <div id="primary" class="content-area">
        <div class="content-space">
          <a class="btn2 w-100 text-center d-block d-lg-none cbtn" href="/" >Choose a Language</a>
          <main id="main" class="site-main" role="main" style="">
              <section id class="sec-row sec-content" style="padding:60px 0 60px 0;">
                <div class="container-fluid group eds-animate" >
                    <div class="row wide-gutter row-eq-height">

                      <div class="col-12 col-md-2 order-2 order-md-1 q-pb-xl">
                          <RightSideBar/>
                      </div>

                      <div class="col-12 col-md-8 order-1 order-md-2">
                          <document-details/>
                          <div class="clearfix"></div>
                      </div>
                      
                      <div class="col-12 col-md-2 order-3 order-md-3 q-pb-xl">
                          <LeftSideBar/>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
              </section>
          </main>
        </div>
        <!-- #main -->
      </div>

      <div class="clearfix"></div>
      <footer id="colophon" class="site-footer" >
        <FooterTop/>
        <FooterMid/>
        <FooterBottom/>
      </footer>
  
    </q-layout>
</template>

<script>
// import $ from 'jquery'
import Header from 'components/static/header/MainHeader.vue'
import RightSideBar from 'components/static/content/sidebar/RightSideBar.vue'
import LeftSideBar from 'components/static/content/sidebar/LeftSideBar.vue'
import DocumentDetails from 'components/static/content/main/Document.vue'
import FooterTop from 'components/static/footer/FooterTop.vue'
import FooterMid from 'components/static/footer/FooterMid.vue'
import FooterBottom from 'components/static/footer/FooterBottom.vue'

export default {
  // name: 'AboutUs',
  components: {
    Header,
    RightSideBar,
    LeftSideBar,
    DocumentDetails,
    FooterTop,
    FooterMid,
    FooterBottom
  },
  data () {
    return {
    }
  },
  methods: {
    testBtn () {
      	console.log('test');
    }
  },
  mounted() {
    this.testBtn();
  }
}
</script>