<template>
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
</template>
<script>
export default {
    name: 'AdsCarousel',
    data () {
    return {
        slide: 1,
    }
  },
}
</script>