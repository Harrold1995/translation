<template>
  <q-card class="document-tiles" flat >
    <q-card-section class="text-center">
      <div  class="text-h6 result-document ellipsis-3-lines ">
           {{name}}
           <q-tooltip v-if="isTooltip(name,'ellipsis3')" class="bg-blue-grey-6" :offset="[10, 10]" anchor="center middle" self="center middle">
            <template v-slot:default>
              <div class="result-tooltip">
                {{name}}
              </div>
            </template>
          </q-tooltip>
      </div>
    </q-card-section>

    <q-card-section class="q-pt-none text-center ">
      <div  class="result-organisation ellipsis-2-lines">
        {{ organisation }}
         <q-tooltip v-if="isTooltip(organisation,'ellipsis2')" class="bg-blue-grey" :offset="[10, 10]" anchor="center middle" self="center middle">
            <template v-slot:default>
              <div class="result-tooltip">
                {{organisation}}
              </div>
            </template>
          </q-tooltip>
      </div>
      <div  class="label-link-validated ellipsis">
        Date of source link last verified as active
        <q-tooltip v-if="isTooltip(' Date of source link last verified as active','ellipsis1')" class="bg-blue-grey" :offset="[10, 10]">
            <template v-slot:default>
              <div class="result-tooltip">
                Date of source link last verified as active
              </div>
            </template>
        </q-tooltip>
      </div>
      <div  class="result-link-validated ellipsis">
        {{last_active}}
        <q-tooltip v-if="isTooltip(last_active,'ellipsis1')" class="bg-blue-grey" :offset="[10, 10]">
            <template v-slot:default>
              <div class="result-tooltip">
                 {{last_active}}
              </div>
            </template>
        </q-tooltip>
      </div>
      <div id="resultBtn">
        <button class="ag-button"> 
            <span class="ag-button-label-1">View Details</span> 
        </button>
      </div>
    </q-card-section>
  </q-card>

</template>

<script>
// import fit from 'components/fittext/fitText.vue'

export default {
  name: "CardDocument",
  components: {
        // fit
    },
  props: ['name', 'organisation', 'last_active'],
  data () {
    return {
        inputText: "test what is the",

        previewOptions: {
          safeWidth: 200,
          previewHeight: 170,
          previewVertOffset: 0,
          originalFontSize: 50
        },
        cols: [
          {
            name: 'test'
          },
          {
            name: 'test'
          }
        ]
    }
  },
  methods: { 
    isTooltip(col, type) {
     
      if(type === 'ellipsis3') {
         return col.length > 96
      }else if(type === 'ellipsis2'){
        return col.length > 64
      }else if(type === 'ellipsis1'){
         return col.length > 51
      }
     
    }
  }
}
</script>

<style scoped>

</style>
