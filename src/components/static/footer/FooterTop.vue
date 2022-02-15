<template>
    <div class="footer-section container">
        <div class="row wide-gutter">
            <div class="col-12 text-center">
                <p style="text-align: center;">A trusted source of information in your language</p>

                <div class="f-logos owl-carousel owl-loaded owl-drag" dir="ltr" >
                <div class="owl-stage-outer">
                    <div class="owl-stage" >
                    <q-carousel
                    swipeable
                    animated
                    v-model="slide"
                    :autoplay="true"
                    ref="carousel"
                    infinite
                    class=""
                    style="max-height: 100px; min-height: 1px;"
                    >
                    <q-carousel-slide :name="1" class="column no-wrap">
                        <div class="row fit justify-start items-center q-gutter-xs q-col-gutter no-wrap">
                        <q-img class="rounded-borders col-4" src="~assets/Logo_DOH.png" />
                        <q-img class="rounded-borders col-4" src="~assets/Logo_NAATI.png" />
                        <q-img class="rounded-borders col-4" src="~assets/Logo_NSW-Health.png" />
                        </div>
                    </q-carousel-slide>
                    <q-carousel-slide :name="2" class="column no-wrap">
                        <div class="row fit justify-start items-center q-gutter-xs q-col-gutter no-wrap">
                        <q-img class="rounded-borders col-4" src="~assets/Logo_ddhs.png" />
                        <q-img class="rounded-borders col-4" src="~assets/Logo_DOH.png" />
                        <q-img class="rounded-borders col-4" src="~assets/Logo_NAATI.png" />
                        </div>
                    </q-carousel-slide>
                    </q-carousel>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'FooterTop',
    data () {
    return {
      slide: 1
    }
  },
}
</script>
