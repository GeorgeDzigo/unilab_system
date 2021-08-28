<template>
    <div class="row">
        <div class="col-md-12 bold-labels">

            <el-row :gutter="20"
                    v-loading="loading"
                    element-loading-text="Loading..."
                    element-loading-spinner="el-icon-loading"
                    element-loading-background="rgba(0, 0, 0, 0.8)">
            <div class="card">
                <div class="card-body row">
                    <div class="form-group mb-3 col-sm-12 required" style="margin-bottom: 2rem !important; margin-top: 1rem;">
                        <label>ქმედება</label>
                        <el-select v-model="form.action" placeholder="აირჩიეთ ქმედება" >
                            <el-option :key="1" :value="1" label="გაცემა"></el-option>
                            <el-option :key="2" :value="2" label="მიღება"></el-option>
                        </el-select>
                    </div>
                    <div class="input-group mb-3 col-sm-6 required" >
                        <div class="input-group-btn">
                            <span class="input-group-text" id="inputGroup-sizing-sm">ID</span>
                        </div>
                        <input type="text" id="item_id" v-on:keyup.enter="checkItem(form.item_id)" v-model="form.item_id"  class="form-control">
                        <div class="input-group-btn">
                            <button class="btn btn-warning" :disabled="loading" @click="checkItem(form.item_id)" >
                                <span class="la la-check" role="presentation" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                    <div class="input-group mb-5 col-sm-6 required">
                        <div class="input-group-btn">
                            <span class="input-group-text" id="inputGroup-sizing-sm3">მოწყობილობის სახელი</span>
                        </div>
                        <input type="text" :disabled="true" v-model="form.item_name"  class="form-control">
                    </div>
                    <div class="input-group mb-3 col-sm-6 required">

                        <div class="input-group-btn">
                            <span class="input-group-text" id="inputGroup-sizing-sm1">პ/ნ</span>
                        </div>
                        <input type="text" v-on:keyup.enter="checkPerson" v-model="form.person_id"  class="form-control">
                        <div class="input-group-btn">
                            <button class="btn btn-warning" :disabled="loading" @click="checkPerson" >
                                <span class="la la-check" role="presentation" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div style="margin-left: 11px;">
                            <PersonalCardScanner :callBackFunction="checkPersonByCardId" :routes="routes"></PersonalCardScanner>
                        </div>
                    </div>
                    <div class="input-group mb-3 col-sm-6 required">
                        <div class="input-group-btn">
                            <span class="input-group-text" id="inputGroup-sizing-sm2">პირის სახელი</span>
                        </div>
                        <input type="text" :disabled="true" v-model="form.person_name"  class="form-control">
                    </div>

                    <div class="row">

                    <div class="form-group col-sm-6 required">
                        <a v-if="person_image" :href="person_image">
                            <img  :src="person_image"  class="img-thumbnail img-fluid" style="width: 400px; height: 300px">
                        </a>
                        <img v-else src="https://www.w3schools.com/bootstrap4/img_avatar3.png"  class="img-thumbnail img-fluid" style="width: 400px;height: 300px">
                    </div>

                    <div class="form-group col-sm-6 required">
                        <qrcode-stream @decode="onDecode" @init="onInit" v-if="!destroyed">
                            <div class="loading-indicator" v-if="loading">
                                Loading...
                            </div>
                        </qrcode-stream>
                    </div>
                    </div>
                </div>
            </div>

                <div id="saveActions" class="form-group">
                    <el-button type="primary" @click="saveLog"
                               :disabled="loading || !form.action || !form.person_id || !form.item_id"
                    >შენახვა</el-button>
                    <el-button :disabled="loading" @click="resetFields">ველების გასუფთავება</el-button>
                </div>

            </el-row>
        </div>
    </div>
</template>

<style>
    .el-message-box {
        width: 371px;
    }
</style>

<script>
    import * as types from '../../store/action-types'
    import { QrcodeStream } from 'vue-qrcode-reader'
    import {getData} from '../../mixins/getData'
    import {responseParse} from '../../mixins/responseParse'
    import { Notification } from 'element-ui';
    import PersonalCardScanner from "../partials/PersonalCardScanner";

    export default {
        store: store,
        components: {PersonalCardScanner, QrcodeStream },
        props: [
            'getDataRoute'
        ],
        data () {
            return {
                routes: {},
                loading: false,
                destroyed: false,
                result: '',
                labelPosition: 'right',
                item: {},
                person_image: '',
                form: {
                    item_id: '',
                    item_name: '',
                    person_id: '',
                    person_name: '',
                    action: ''
                }
            }
        },

        created(){
          this.getData();
        },

        methods: {

            checkPersonByCardId(){

                // Set card id
                this.form.card_id = this.$store.getters.cardId;

                this.checkPerson();

            },

            /**
             *
             * @returns {Promise<void>}
             */
            async getData(){

                this.loading = true;

                await getData({
                    method: 'POST',
                    url: this.getDataRoute,
                }).then(response => {

                    // Parse response notification.
                    responseParse(response, false);

                    if (response.code == 200) {

                        let data = response.data;

                        this.routes = data.routes;

                    }

                    this.loading = false;

                });

            },

            /**
             *
             * @param result
             */
            onDecode (result) {

                try {
                    this.result = JSON.parse(result);
                } catch (e) {
                    Notification.error({
                        title: '',
                        message: 'შეცდომა Qr-ის სკანირებისას, სცადეთ თავიდან',
                        offset: 100,
                    });
                }

                if (this.result.id) {
                    this.checkItem(this.result.id);
                }
            },

            /**
             *
             * @returns {Promise<void>}
             */
            async checkPerson(){

                this.loading = true;

                await getData({
                    method: 'POST',
                    url: this.routes.checkPersonRoute,
                    data: { personal_number: this.form.person_id, card_id: this.form.card_id }
                }).then(response => {

                    // Parse response notification.
                    responseParse(response);

                    if (response.code == 200) {

                        let data = response.data;

                        this.form.person_name = data.person.fullName;
                        this.form.person_id = data.person.personal_number;
                        this.person_image = data.person.frontCardImage;
                    }

                    this.loading = false;

                });

            },

            /**
             *
             * @returns {Promise<void>}
             */
            async saveLog(){

                this.$confirm('დარწმუნებული ხართ, რომ გსურთ შენახვა?')
                    .then(async () => {

                        this.loading = true;

                        await getData({
                            method: 'POST',
                            url: this.routes.saveLogRoute,
                            data: this.form
                        }).then(response => {

                            // Parse response notification.
                            responseParse(response);

                            if (response.code == 200) {
                                this.resetFields();
                            }

                            this.loading = false;

                        });

                });



            },

            /**
             *
             * @param itemId
             * @returns {Promise<void>}
             */
            async checkItem(itemId){

                this.loading = true;

                await getData({
                    method: 'POST',
                    url: this.routes.checkItemRoute,
                    data: { id: itemId }
                }).then(response => {

                    // Parse response notification.
                    responseParse(response);

                    if (response.code == 200) {

                        let data = response.data;

                        this.form.item_name = data.item.name;
                        this.form.item_id = data.item.id;

                    }

                    this.loading = false;

                });

            },

            resetFields(){
                this.reload();
                this.form = {
                    item_id: '',
                    item_name: '',
                    person_id: '',
                    person_name: '',
                    action: ''
                };
                this.person_image = '';
            },

            /**
             *
             * @param promise
             * @returns {Promise<void>}
             */
            async onInit (promise) {
                this.loading = true

                try {
                    await promise
                } catch (error) {
                    console.error(error)
                } finally {
                    this.loading = false
                }
            },

            async reload () {
                this.destroyed = true

                await this.$nextTick()

                this.destroyed = false
            }
        }
    }
</script>

<style scoped>
    button {
        margin-bottom: 20px;
    }

    .loading-indicator {
        font-weight: bold;
        font-size: 2rem;
        text-align: center;
    }

    .wrapper[data-v-1f90552a] {
        width: 80% !important;
    }

</style>
