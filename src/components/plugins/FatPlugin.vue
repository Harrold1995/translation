<template>
    <div class="ag-plugin-content">
        <div class="top-right-info">
            <span class="language-in"> {{documents.language}} </span>
        </div>

        <div id="FATengine" v-if="engineMode">
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
                    <input type="text" name="document-search-search" id="document-search-search" v-model="filterRequests.search" placeholder="SEARCH HERE..">
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
                    <span class="search-button-label lang" key="search" >SEARCH</span> 
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
                    :rows="documents.data" 
                    :columns="columns" 
                    :filter="filterRequests"
                    :loading="loading"
                    v-model:pagination="pagination"
                    @request="dataTablesRequests"
                    :rows-per-page-options="rowsOptions"
                    style=""
                    class=""
                    >
                        <template v-slot:item="props" class="">
                        <div class="row test">
                            <div class="q-pa-lg col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                <card-document :id="props.row.id" :criteria="props.row.criteria" :name="props.row.name" :organisation="props.row.organisation" :last_active="props.row.last_verified"></card-document>
                            </div>
                        </div>
                        </template>
                    </q-table>
                </q-card-section>
            </q-card>

        </div>

        <div id="FATdetails" v-else>
            
            <!-- 
                *** Search Box Accordion
            -->
            <div class="accordion-container" @click="backToResult">
                <span class="accordion-title">SEARCH FOR TRANSLATIONS
                    <hr class="accordion-title-hr"> 
                </span>
                <div class="accordion-content">
                        <!-- This is where accordion content will put -->
                </div>
            </div>

            <!-- 
            *** Document View Details
            -->

            <div class="document-details">
                <div class="document-details-body">
                    <div class="document-details-header">
                        <h2 class="document-detail-name"> {{document.result.document_name}} </h2>
                        <hr class="document-detail-hr">
                        <div class="document-detail-file container">
                            <div class="document-detail-file-content row">
                                <div class="col-sm-1 document-detail-file-content-col1">
                                    <span class="document-detail-file-content-icon-pdf "></span> &nbsp;
                                </div>
                                <div class="col-sm-11 document-detail-file-content-col2">
                                    <span class="document-detail-file-content-text">
                                    {{document.result.language_id == 256 ? 'English Resource: ' : 'Resource: '}} 
                                    </span>
                                    <span class="document-detail-file-content-text"> {{document.result.document_name}} </span>
                                    <a @click="openDocumentLink(s_id,s_criteria)" class="link-spanner"></a>
                                </div>
                            </div>
                        </div>
                         <br>
                        <div class="document-detail-file container" v-if="document.result.language_id != 256">
                            <div class="document-detail-file-content row">
                                <div class="col-sm-1 document-detail-file-content-col1">
                                    <span class="document-detail-file-content-icon-pdf "></span> &nbsp;
                                </div>
                                <div class="col-sm-11 document-detail-file-content-col2">
                                    <span class="document-detail-file-content-text">
                                    English Resource: </span>
                                    <span v-for="(item, index) in document.result.source" :key="index"> 
                                        <span class="document-detail-file-content-text">
                                            {{item.source_document_language.document_name}} 
                                        </span>
                                        <a @click="openDocumentLink(item.source_document_language.id,s_criteria)" class="link-spanner"></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="document-details-contents row">
                        <div class="document-details-contents-1 col-md-6 col-sm-6">
                            <div class="document-details-content-text-left">
                                <span class="document-details-content-label-1 lang" key="source">Source </span><br>
                                <span class="document-details-content-text">  {{document.result.document.organisation}} </span>
                            </div>
                            <div class="document-details-content-text-left">
                                <span class="document-details-content-label-1 lang" key="language">Language </span><br>
                                <span class="document-details-content-text">  {{document.result.language.language}} </span>
                            </div>
                            <div class="document-details-content-text-left">
                                <span class="document-details-content-label-1 lang" key="file">File </span><br>
                                <span class="document-details-content-text"> {{document.result.document.type}} </span>
                            </div>
                            <div class="document-details-content-text-left">
                                <span class="document-details-content-label-1 lang" key="multilingual">Multilingual </span><br>
                                <span class="document-details-content-text"> {{document.multilingual ? 'YES' : 'NO'}} </span>
                            </div>
                            <div class="document-details-content-text-left">
                                <span class="document-details-content-label-3 lang" key="date_of_source_link">Date of source link last verified as active </span><br>
                                <span class="document-details-content-text-1">{{dateFormat(document.result.document.link[0].url_check[0].last_run)}} </span>
                            </div>
                        </div>
                        <div class="document-details-contents-2 col-md-6 col-sm-6">
                            <span class="document-details-content-label-2 lang" key="description">Description </span><br>
                            <br>
                            <span class="document-details-content-text"> NOT SUPPLIED </span>
                        </div>
                    </div>
                    <div class="document-details-footer" v-if="document.result.language_id == 256">
                        <strong><span class="document-details-content-label-2 lang" key="translations">Translations </span></strong>
                        <br>
                        <br>
                        <div v-for="(item, index) in document.result.target" :key="index">
                            <div class="document-detail-file container">
                                <div class="document-detail-file-content row">
                                    <div class="col-sm-1 document-detail-file-content-col1">
                                        <span class=" document-detail-file-content-icon-pdf "></span> &nbsp;
                                    </div>
                                    <div class="col-sm-11 document-detail-file-content-col2">
                                        <span class="document-detail-file-content-text"> [{{item.target_document_language.language.language}}] - </span><span class="document-detail-file-content-text" dir="auto">{{item.target_document_language.document_name}}</span>
                                        <a @click="openDocumentLink(item.target_document_language.id,s_criteria)" class="link-spanner"></a>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>

             <div class="back-to-search-result">
                <span id="btn-back-to-search-result" class="back-to-search-result-label" @click="backToResult"> BACK TO SEARCH RESULTS </span>
            </div>
        
        </div>

    </div>
</template>
<script>
import { mapState, mapActions } from 'vuex'
import { date, useQuasar, QSpinnerIos } from 'quasar'
import { fatService } from '../../services'
import CardDocument from 'components/plugins/components/CardDocument.vue'

export default {
    name: 'FatPlugin',
    components: {
        CardDocument
    },
    data () {
        return {
            q: useQuasar(),
            filter: '',
            loading: false,
            rowsOptions: [10, 20, 50, 100, 0],
            pagination: {
                sortBy: 'desc',
                descending: false,
                page: 1,
                rowsPerPage: 9,
                rowsNumber: 9
            },
            filterRequests: {
                search: ''
            },
            engineMode: true,
            s_id: '',
            s_criteria: ''

            
        }
    }, 
    methods: {
        ...mapActions('fat', ['fatAction','fatDetailAction']),
        pluginLoad () {
             let sid = this.$route.query.sid
             let scriteria = this.$route.query.scriteria

             if(sid == undefined && scriteria == undefined) {
                 this.engineMode = true
             } else {
                 this.engineMode = false
                 this.loadDocumentDetails();
             }

        },
        loadDocumentDetails () {
            this.s_id = this.$route.query.sid
            let scriteria = this.$route.query.scriteria
            const scriteria_id = scriteria.split("_");
            this.s_criteria = scriteria_id[0];

            this.q.loading.show({
                delay: 400,
                spinnerColor: 'orange-9',
                spinner: QSpinnerIos,
                spinnerSize: 100,
            })

            const payload  = {
                id:  this.s_id,
                criteria: this.s_criteria
            }
            this.fatDetailAction(payload)
            .then(response => {
                this.q.loading.hide()
            })
            .catch(e => {
                console.log(e)
            })

        },
        dataTablesRequests (props) {
            const { page, rowsPerPage, sortBy, descending } = props.pagination
            this.loading = true

            setTimeout(() => {
                const fetchCount = rowsPerPage === 0 ? this.paginationRequests.rowsNumber : rowsPerPage

                console.log(this.$route.params.language == undefined ? 'en' : this.$route.params.language)
                const payload  = {
                page: page,
                itemPerRow: fetchCount,
                document: this.filterRequests.search,
                language: this.$route.params.language == undefined ? 'en' : this.$route.params.language,
                draw: 2
                }
                console.log(payload)

                this.serverDataRequests(payload)

                this.pagination.page = page
                this.pagination.rowsPerPage = rowsPerPage
                this.pagination.sortBy = sortBy
                this.pagination.descending = descending
            }, 1000)
        },
        serverDataRequests (payload) {
            this.fatAction(payload)
            .then(response => {
            this.pagination.rowsNumber = response.total
            this.loading = false
            console.log(response)
            })
            .catch(e => {
                console.log(e)
            })
        },
        dateFormat (value) {
          const formattedString = date.formatDate(value, 'MMMM DD, YYYY')
          console.log(value)
          return formattedString
        },
        openDocumentLink (id,criteria) {

            const payload  = {
                id: id,
                criteria: criteria,
            }
            this.q.loading.show({
                delay: 400,
                spinnerColor: 'orange-9',
                spinner: QSpinnerIos,
                spinnerSize: 100,
            })

           fatService.fatProcessLinkApi(payload)
            .then(response => {
                let result = response.data;
                console.log(result)

                let shortURL = result['short_url'];
                setTimeout(function() { 
                    window.open(
                        shortURL,
                        '_blank'
                    );
                }, 2000);
                this.q.loading.hide()
               
            })
            .catch(e => {
                console.log(e)
            })
            
        },
        backToResult () {
            const payload  = {
                criteria: this.s_criteria
            }

            fatService.fatGetSearchCriteriaApi(payload)
            .then(response => {
                let result = response.data;


                this.filterRequests.search = result['criteria']['Keyword'];
                this.engineMode = true;
                this.dataTablesRequests({
                    pagination: this.pagination,
                    filter: this.filterRequests
                })
                window.scrollTo(0,0)
                // let tags = theResponse['tags']
                // let organisations = theResponse['organisations']
                // let states = theResponse['states']
                // let type = theResponse['type']
                console.log(keyword)
            })
            .catch(e => {
                console.log(e)
            })
        }
    },
    computed: {
    ...mapState({
        documents: state => state.fat.documents,
        document: state => state.fat.document
    })
    },
    mounted () {
    this.dataTablesRequests({
      pagination: this.pagination,
      filter: this.filterRequests
    }),
    this.pluginLoad();
  }
}
</script>