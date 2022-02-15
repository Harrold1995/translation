<template>
    <div class="h-100 position-relative float-left w-100">
        <div class="fixedwidget sticky-top">

            <div class="owl-carousel gallery-carousel owl-loaded owl-drag" dir="ltr">
            <q-carousel
            swipeable
            animated
            v-model="slide"
            :autoplay="true"
            ref="carousel"
            infinite
            >
            <q-carousel-slide :name="1" img-src="~assets/main1.jpg" />
            <q-carousel-slide :name="2" img-src="~assets/main2.jpg" />
            <q-carousel-slide :name="3" img-src="~assets/main3.jpg" />
            <q-carousel-slide :name="4" img-src="~assets/main4.jpg" />
            </q-carousel>
            </div>

            <p>
            <a href="#" target="_blank" rel="noopener">
                <img loading="lazy" class="aligncenter" src="~assets/AD-PLACEHOLDER_Request-a-Translation.jpg" alt width="300" height="600" 
                srcset="~assets/AD-PLACEHOLDER_Request-a-Translation.jpg 300w, images/AD-PLACEHOLDER_Request-a-Translation-150x300.jpg 150w" 
                sizes="(min-width: 1023px) 100vw, 1023px">
            </a>
            </p>
        </div>
    </div>
</template>
<script>
export default {
    name: 'RightSideBar',
    data () {
    return {
        slide: 1,
    }
  },
}
</script>
