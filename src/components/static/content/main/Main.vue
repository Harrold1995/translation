<template>
    <div class="element-row">
        <div class="content-container ">
            <div class="ag-plugin-content">
                <div class="top-right-info">
                    <span class="language-in"> English </span>
                </div>
                <!-- 
                *** Main Search
                -->
                <div class="search-box ">
                    <div class="col-md-12">
                        <h3 class=""> SEARCH FOR TRANSLATIONS
                            <span class="search-instruction">
                                <sup> <i class="fas fa-info-circle"></i></sup>
                                <span class="tooltiptext-instruction">Type words to find translations </span>
                            </span>
                        </h3>
                    </div>
                    <div class="main-search">
                        <span class="fa fa-search"></span>
                        <input type="text" name="document-search-search" id="document-search-search" placeholder="SEARCH HERE..">
                    </div>
                    <br> <br> 
                    <div id="accordion-advance-search">
                        <div class="advance-search">
                            <span class="advance-search-btn"> 
                                <span class="advance-search-text lang" key="advanced_search">ADVANCED SEARCH  </span>
                            </span>
                        </div>
                    </div>
                    <br><br>  
                    <button id="document-search-submit" class="search-button"> 
                        <span class="search-button-label lang" key="search">SEARCH</span> 
                    </button>
                </div>
                <br>
                <!-- 
                *** Document View Details
                -->

                <q-card class="document-table-results" flat>
                    <q-card-section>
                    <div class="text-h6 text-grey-8">
                        RESOURCES
                    </div>
                    </q-card-section>
                    <hr class="table-title-hr"/>
                    <q-card-section class="q-pa-none">
                        <q-table
                        grid 
                        :rows="data" 
                        :columns="columns" 
                        :filter="filter"
                        v-model:pagination="pagination"
                        style=""
                        class=""
                        >
                            <!-- <template v-slot:top-right>
                                <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
                                <template v-slot:append>
                                    <q-icon name="search" />
                                </template>
                                </q-input>
                            </template> -->
                            <template v-slot:item="props" class="">
                            <div class="row test">
                                <div class="q-pa-lg col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                    <card-document :name="props.row.name" :organisation="props.row.organisation" :last_active="props.row.last_active"></card-document>
                                </div>
                            </div>
                            </template>
                        </q-table>
                    </q-card-section>
                </q-card>

            </div>
        </div>
    </div>
</template>
<script>

import CardDocument from 'components/static/cards/CardDocument.vue'

export default {
    name: 'Main',
    components: {
        CardDocument
    },
    data () {
    return {
        filter: '',
        pagination: {
            page: 1,
            rowsPerPage: 3
        },
        // columns: [
        // {name: 'Name', label: 'Name', field: 'name', sortable: true, align: 'left'},
        // {name: 'Crated Date', label: 'Crated Date', field: 'Crated_Date', sortable: true, align: 'left'},
        // {name: 'Project', label: 'Project', field: 'Project', sortable: true, align: 'left'},
        // {name: 'Action', label: '', field: 'Action', sortable: false, align: 'center'}
        // ],
        data: [
        {
            name: 'Bladder Control Problem create new line for this to test if will work on how the test for testing',
            last_active: 'October 29, 2021',
            organisation: 'Continence Foundation of Australia Continence Foundation of Australia',
        },
        {
            name: 'A Guide to Enrolling and Voting',
            last_active: 'October 29, 2021',
            organisation: 'Australian Electoral Commission',
        },
        {
            name: 'Your Guide to Survival',
            last_active: 'October 29, 2021',
            organisation: 'Country Fire Authority',
        },
        {
            name: 'Fire Orders',
            last_active: 'November 1, 2021',
            organisation: 'Country Fire Authority',
        },
        {
            name: 'Top tips for staying safe online',
            last_active: 'November 1, 2021',
            organisation: 'Services Australia',
        }
        ]
    }
  },
}
</script>