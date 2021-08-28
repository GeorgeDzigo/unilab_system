<template>
    <div class="row">
        <div class="col-md-3 bold-labels">

            <div class="card" >
                <div class="card-header" style="font-weight: bold;">
                    მომხმარებლის ინფორმაცია
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item " :class="{'active': tab == 1}" @click="tab = 1" style="cursor: pointer;">
                        <i class="el-icon-s-check"></i> პირადი ინფორმაცია
                    </li>
                    <li class="list-group-item " :class="{'active': tab == 2}" @click="tab = 2" style="cursor: pointer;">
                        <i class="el-icon-phone"></i> საკონტაქტო / მისამართები
                    </li>
                    <li class="list-group-item " :class="{'active': tab == 3}" @click="tab = 3" style="cursor: pointer;">
                        <i class="el-icon-s-management"></i> პოზიცია / დეპარტამენტი
                    </li>
                    <li class="list-group-item " :class="{'active': tab == 4}" @click="tab = 4" style="cursor: pointer;">
                        <i class="el-icon-bank-card"></i> პირადობა
                    </li>
                </ul>
            </div>

        </div>
        <div class="col-md-9 bold-labels">
            <div class="card">

            <div class="card-body row" v-if="tab == 1">

                <div class="form-group col-sm-6 required">
                    <label>სახელი</label>
                    <input type="text" v-model="form.first_name" class="form-control">
                </div>

                <div class="form-group col-sm-6 required">
                    <label>გვარი</label>
                    <input type="text" v-model="form.last_name" class="form-control">
                </div>

                <div class="form-group col-sm-2 required">
                    <label>პ/ნ</label>
                    <input type="text" :maxlength="maxPersonalNumber" v-model="form.personal_number" class="form-control">
                </div>

                <div class="form-group col-sm-2 required" style="margin-top: 1rem;">
                    <label>ბარათის ID</label>
                    <p style="color: red">{{ form.card_id }}</p>
                </div>

                <div class="form-group col-sm-2 required" style="margin-top: 2rem;">
                    <PersonalCardScanner :callBackFunction="scanCard" :routes="routes"></PersonalCardScanner>
                </div>

                <div class="form-group col-sm-6 required" style="margin-top: 1rem;">
                    <label>Biostar მომხმარებლის ID</label>
                    <input type="text" v-model="form.biostar_card_id" class="form-control">
                </div>

                <div class="form-group col-md-4" style="margin-top: 30px;">
                    <label>დაბადების თარიღი</label>
                    <el-date-picker
                        v-model="form.birth_date"
                        type="date"
                        format="yyyy-MM-dd"
                        value-format="yyyy-MM-dd"
                        placeholder="აირჩიეთ თარიღი">
                    </el-date-picker>
                </div>

                <div class="form-group col-md-4" style="margin-top: 30px;">
                    <label>სქესი</label>
                    <el-select v-model="form.gender" cleareble placeholder="აირჩიეთ სქესი">
                        <el-option :key="1" label="კაცი" :value="1"></el-option>
                        <el-option :key="2" label="ქალი" :value="2"></el-option>
                    </el-select>
                </div>
            </div>

            <div class="card-body row" v-if="tab == 2">

                <div class="form-group col-md-3">
                    <label>ქალაქი</label>
                    <el-select v-model="form.additional_info.address.city" cleareble placeholder="აირჩიეთ ქალაქი">
                        <el-option :key="1" label="თბილისი" value="tbilisi"></el-option>
                    </el-select>
                </div>

                <div class="form-group col-sm-3 required">
                    <label>მისამართი</label>
                    <textarea rows="5" v-model="form.additional_info.address.address" class="form-control"></textarea>
                </div>

                <div class="form-group col-sm-3 required">
                    <label>მობილური ნომერი</label>
                    <input type="text"  v-model="form.additional_info.contact.mobile" class="form-control">
                </div>

                <div class="form-group col-sm-3 required">
                    <label>მობილური ნომერი 2</label>
                    <input type="text"  v-model="form.additional_info.contact.mobile2" class="form-control">
                </div>

                <div class="form-group col-sm-6 required">
                    <label>უნილაბის მეილი</label>
                    <input type="text"  v-model="form.unilab_email" class="form-control">
                </div>

                <div class="form-group col-sm-6 required">
                    <label>პირადი მეილი</label>
                    <input type="text"  v-model="form.personal_email" class="form-control">
                </div>

                <div class="form-group col-md-12">
                    <hr style="border-top: 1px solid red;  border-radius: 5px;">
                </div>
                <div class="form-group col-sm-2 required">
                    <label>პასუხისმგებელი პირი?</label><br>
                    <el-radio v-model="form.additional_info.family_contact.status" :label="2">არა</el-radio>
                    <el-radio v-model="form.additional_info.family_contact.status" :label="1">კი</el-radio>
                </div>

                <div class="form-group col-sm-3 required" v-if="form.additional_info.family_contact.status == 1">
                    <label>პირის სტატუსი</label>
                    <el-select v-model="form.additional_info.family_contact.person_type" cleareble placeholder="აირჩიეთ პირის სტატუსი">
                        <el-option :key="1" label="დედა" value="mother"></el-option>
                        <el-option :key="2" label="მამა" value="father"></el-option>
                        <el-option :key="3" label="ძმა" value="brother"></el-option>
                        <el-option :key="4" label="და" value="sister"></el-option>
                        <el-option :key="5" label="ბებია" value="grandmother"></el-option>
                        <el-option :key="6" label="ბაბუა" value="grandfather"></el-option>
                        <el-option :key="7" label="ბიძა" value="uncle"></el-option>
                        <el-option :key="8" label="დეიდა" value="aunt"></el-option>
                    </el-select>
                </div>

                <div class="form-group col-sm-3 required" v-if="form.additional_info.family_contact.status == 1">
                    <label>სახელი/გვარი</label>
                    <input type="text"  v-model="form.additional_info.family_contact.full_name" class="form-control">
                </div>

                <div class="form-group col-sm-4 required" v-if="form.additional_info.family_contact.status == 1">
                    <label>მობილური</label>
                    <input type="text"  v-model="form.additional_info.family_contact.mobile" class="form-control">
                </div>

                <div class="form-group col-sm-4 required" v-if="form.additional_info.family_contact.status == 1">
                    <label>პ/ნ</label>
                    <input type="text"  v-model="form.additional_info.family_contact.personal_number" class="form-control">
                </div>

                <div class="form-group col-sm-3 required" v-if="form.additional_info.family_contact.status == 1">
                    <label>ბარათის კოდი</label>
                    <p style="color: red">{{ form.additional_info.family_contact.personal_card_id }}</p>
                </div>

                <div class="form-group col-sm-4 required" style="margin-top: 2rem;" v-if="form.additional_info.family_contact.status == 1">
                    <PersonalCardScanner :callBackFunction="scanFamilyContactId" :routes="routes"></PersonalCardScanner>
                </div>

                <div class="form-group col-sm-12 required" v-if="form.additional_info.family_contact.status == 1">
<!--                    <PersonalCardCapture  :person="person" :personalImages="this.person ? this.person.familyContactPersonalCards : []" :onChange="captureFamilyContactId"></PersonalCardCapture>-->
                </div>

            </div>

            <div class="card-body row" v-if="tab == 3">

                <div class="input-group border border-primary col-md-12" style="padding: 36px;margin-bottom: 1rem;"
                     v-for="(line, index) in form.positions" v-bind:key="index">
                    <label></label>
                    <div class="form-group col-md-3 required">
                        <label>პოზიცია</label>
                        <el-select v-model="line.position" placeholder="აირჩიეთ პოზიცია" cleareble >
                            <el-option
                                v-for="item in options.positions"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                            </el-option>
                        </el-select>
                    </div>

                    <div class="form-group col-md-3">
                        <label>დეპარტამენტი</label>
                        <el-select v-model="line.department" cleareble placeholder="აირჩიეთ დეპარტამენტი">
                            <el-option
                                v-for="item in options.departments"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                            </el-option>
                        </el-select>
                    </div>

                    <div class="form-group col-md-3 required">
                        <label>პოზიციის ვადა</label>
                        <select v-model="line.date_type" placeholder="აირჩიეთ ვადა" class="form-control">
                            <option value="1">ვადით განსაზღვრული</option>
                            <option value="2">უვადოდ</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4" v-if="line.date_type == 1">
                        <label>დაწყება/დასრულება</label>
                        <el-date-picker
                            v-model="line.start_to"
                            type="daterange"
                            range-separator="-"
                            start-placeholder="დან"
                            end-placeholder="მდე">
                        </el-date-picker>
                    </div>

                    <div class="form-group col-md-4" v-if="line.date_type == 2" style="margin-top: 30px;">
                        <label>დაწყება</label>
                        <el-date-picker
                            v-model="line.start"
                            type="date"
                            placeholder="აირჩიეთ თარიღი">
                        </el-date-picker>
                    </div>

                    <div class="form-group col-sm-2 required">
                        <label>დოკ. ნომერი</label>
                        <input type="text"  v-model="line.document_number" class="form-control">
                    </div>

                    <div class="form-group col-md-2" style="margin-top: 30px;">
                        <button type="button"  class="btn btn-danger" @click="removeLine(index)">
                            წაშლა
                        </button>
                    </div>

                </div>

                <div class="form-group col-md-12">
                    <button @click="addLine({})" type="button" class="btn btn-success">
                        პოზიციის დამატება
                    </button>
                </div>

            </div>

            <div class="card-body row" v-if="tab == 4">

                <div class="form-group col-md-12">

                    <PersonalCardCapture :person="person" :personalImages="this.person && this.person.personalCards ? this.person.personalCards : []" :onChange="captureImages"></PersonalCardCapture>

                </div>

            </div>

        </div>
            <div id="saveActions" class="form-group">

            <input type="hidden" name="save_action" value="save_and_back">
            <div class="btn-group" role="group">
                <button @click="saveData" type="button" class="btn btn-success">
                    <span class="la la-save" role="presentation" aria-hidden="true"></span> &nbsp;
                    <span data-value="save_and_back">შენახვა</span>
                </button>
            </div>

        </div>
        </div>
    </div>
</template>

<script>
    import * as types from '../../store/action-types'
    import {getData} from '../../mixins/getData'
    import {responseParse} from '../../mixins/responseParse'
    import PersonalCardScanner from '../partials/PersonalCardScanner'
    import PersonalCardCapture from './partials/PersonalCardCapture'
    import { Notification } from 'element-ui';

    export default {
        store: store,
        components: {PersonalCardScanner,PersonalCardCapture},
        props: [
            'getSaveDataRoute',
            'person'
        ],
        data(){
            return {
                tab: 1,
                loading: false,
                maxPersonalNumber: 11,
                form: {
                    additional_info: {
                        address: {},
                        contact: {},
                        family_contact: {},
                        personal_info: {},
                        personal_card: {}
                    }
                },
                imageUrl: '',
                fileList: [],
                options: {
                    positions: [],
                    departments: []
                },
                routes: {}
            }
        },
        watch: {
            'form.additional_info.family_contact.status': function (){
                if (this.form.additional_info.family_contact.status == 2) {
                    this.form.additional_info.family_contact.person_type = '';
                    this.form.additional_info.family_contact.full_name = '';
                    this.form.additional_info.family_contact.mobile = '';
                    this.form.additional_info.family_contact.personal_number = '';
                    this.form.additional_info.family_contact.personal_card_id = '';
                    this.form.additional_info.family_contact.images = {};
                }
            }
        },
        created(){
            this.getSaveData();
        },

        methods: {

            /**
             * Capture images.
             *
             */
            captureImages(images){
                this.form.additional_info.personal_card = {
                    images: images
                };
            },

            /**
             * Capture family contact id.
             */
            captureFamilyContactId(images){
                this.form.additional_info.family_contact.images = images;
            },

            scanFamilyContactId(cardId){
                this.form.additional_info.family_contact.personal_card_id = cardId;
            },

            scanCard(cardId){
                this.form.card_id = cardId;
            },

            /**
             * Set defaults fields.
             *
             */
            async setDefaultFields(){

                let positions = [];
                let additional_info = {
                    address: {
                        address: '',
                        city: ''
                    },
                    contact: {
                        mobile: ''
                    },
                    personal_info: {},
                    family_contact: {
                        status: 2,
                        images: {},
                        personal_card_id: ''
                    },
                    personal_card: {
                        images: {}
                    }
                };

                if (this.person) {

                    this.imageUrl = this.person.imageSrc;

                    this.person.positions.forEach((item) => {

                        let dateType = 1;
                        let startTo = [];

                        if (item.end_date == null) {
                            dateType = 2;
                        } else {
                            startTo = [
                                item.start_date,
                                item.end_date
                            ];
                        }

                        positions.push({
                            id: item.id,
                            position: item.position_id,
                            department: item.department_id,
                            date_type: dateType,
                            start_to: startTo,
                            start: item.start_date,
                            document_number: item.doc_number
                        });

                    });

                }

                this.form = {
                    card_id:        this.person ? this.person.card_id : '',
                    biostar_card_id: this.person ? this.person.biostar_card_id : '',
                    id:             this.person ? this.person.id : '',
                    first_name:     this.person ? this.person.first_name : '',
                    last_name:      this.person ? this.person.last_name : '',
                    personal_number: this.person ? this.person.personal_number : '',
                    mobile_number:  this.person ? this.person.mobile_number : '',
                    phone_number:   this.person ? this.person.phone_number : '',
                    status:         this.person ? this.person.status == 1 : '',
                    gender:         this.person ? this.person.gender : '',
                    birth_date:     this.person ? this.person.formatBirthDate : '',
                    unilab_email:   this.person ? this.person.unilab_email: '',
                    personal_email: this.person ? this.person.personal_email: '',
                    positions:      positions,
                    additional_info: this.person && this.person.additional_info ?  this.person.additional_info : additional_info
                };

                this.$store.dispatch(types.CARD_ID, this.form.card_id);

            },

            /**
             *
             * Get save data object.
             *
             * @returns {Promise<void>}
             */
            async getSaveData(){

                this.loadingPage(true);

                await getData({
                    method: 'POST',
                    url: this.getSaveDataRoute,
                }).then(response => {

                    // Parse response notification.
                    responseParse(response, false);

                    if (response.code == 200) {

                        /**
                         * Response data.
                         *
                         * @type {T}
                         */
                        let data = response.data;

                        this.options = data.options;
                        this.routes = data.routes;

                        this.setDefaultFields();

                    }

                    this.loadingPage(false);

                })

            },

            async saveData(){

                this.$confirm('დარწმუნებული ხართ, რომ გსურთ შენახვა?', 'ყურადღება!', {
                    confirmButtonText: 'დიახ',
                    cancelButtonText: 'არა',
                    type: 'warning'
                }).then(async () => {

                    this.loadingPage(true);

                    this.form.additional_info.address = {
                        address: this.form.additional_info.address.address,
                        city: this.form.additional_info.address.city
                    };

                    this.form.additional_info.contact = {
                        mobile: this.form.additional_info.contact.mobile,
                        mobile2: this.form.additional_info.contact.mobile2
                    };

                    await getData({
                        method: 'POST',
                        url: this.routes.save,
                        data: this.form
                    }).then(response => {

                        // Parse response notification.
                        responseParse(response);

                        if (response.code == 200) {

                            /**
                             * Response data.
                             *
                             * @type {T}
                             */
                            let data = response.data;

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);


                        }

                        this.loadingPage(false);

                    })

                });

            },

            /**
             *
             * Add Multiple line method.
             *
             * @param addObject
             */
            addLine(addObject) {

                let checkEmptyLines = this.form.positions.filter(line => line.number === null)
                if (checkEmptyLines.length >= 1 && this.form.positions.length > 0) return
                this.form.positions.push(addObject)
            },

            loadingPage(status) {

                if (status) {
                    this.loadingObject = this.$loading({
                        lock: true,
                        text: 'იტვირთება',
                        spinner: 'el-icon-loading',
                        background: 'rgba(0, 0, 0, 0.7)'
                    });
                } else {
                    this.loadingObject.close();

                }
            },

            /**
             *
             * Remove multiple line method.
             *
             * @param lineId
             */
            removeLine(lineId) {
                this.form.positions.splice(lineId, 1)
            },

            handleOnRemove(file,fileList){
                this.imageUrl = '';
                this.fileList= [];
                this.form.tempImageId = '';
            },

            handleAvatarSuccess(res, file, fileList) {
                this.form.tempImageId = file.response.data.tempImage.id
                this.loading = false;
                this.imageUrl = URL.createObjectURL(file.raw);
                this.fileList = fileList;
            },

            beforeAvatarUpload(file) {
                this.loading = true;
                return true;
            },


            handleError(err, file, fileList){
                Notification.error({
                    title: '',
                    message: 'შეცდომა, გთხოვთ სცადოთ თავიდან',
                    offset: 100,
                });
            },

            /**
             *
             * @param file
             * @param fileList
             */
            onExceedLimit(file, fileList){
                Notification.error({
                    title: '',
                    message: 'შეცდომა, თქვენ შეგიძლიათ მხოლოდ 1 ფოტოს ატვირთვა!',
                    offset: 100,
                });
            },

        }

    }

</script>
