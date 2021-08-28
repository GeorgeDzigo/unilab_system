<template>
    <div class="row">
        <div class="col-md-12">
            <div class="border">
                <el-switch
                    style="margin-left: 1rem !important;"
                    title="კამერის ჩართვა/გამორთვა"
                    v-model="cameraStatus"
                    active-color="#13ce66"
                    inactive-color="#ff4949"
                    active-text="ჩართულია"
                    inactive-text="გათიშული">
                </el-switch>
                <vue-web-cam
                    ref="webcam"
                    :device-id="deviceId"
                    width="100%"
                    @error="onError"
                    @cameras="onCameras"
                    @camera-change="onCameraChange"
                />
            </div>

            <div class="row">
                <div class="col-md-4" style="margin-top: 2rem;">
                    <label>მოწყობილობა</label>
                    <el-select v-model="camera" placeholder="აირჩიეთ მოწყობილობა">
                        <el-option
                            v-for="device in devices"
                            :key="device.deviceId"
                            :value="device.deviceId"
                            :label="device.label"
                        ></el-option>
                    </el-select>
                </div>
                <div class="col-md-4" style="margin-top: 2rem;">
                    <label>პირადობის მხარე</label>
                    <el-select v-model="side" placeholder="აირჩიეთ პირადობის მხარე">
                        <el-option :key="1" value="front" label="წინ"></el-option>
                        <el-option :key="2" value="back" label="უკან"></el-option>
                    </el-select>
                </div>
                <div class="col-md-4" style="margin-top: 1.8rem;margin-bottom: 1.5rem;">
                    <el-button icon="el-icon-camera-solid"  type="success" @click="onCapture">გადაღება</el-button>
                </div>
                <div class="col-md-12">
                    <el-button v-if="person" type="primary" icon="el-icon-printer" v-print="printObj" style="margin-bottom: 1rem;">ბეჭდვა</el-button>
                </div>
            </div>
        </div>

        <div id="printMe" class="row">
            <div class="col-md-6">
                <h2>წინა მხარე</h2>
                <figure class="figure">
                    <img :src="img.front" style="width: 500px;" class="img-responsive" />
                </figure>
            </div>
            <div class="col-md-6">
                <h2>უკანა მხარე</h2>
                <figure class="figure">
                    <img :src="img.back" style="width: 500px;" class="img-responsive" />
                </figure>
            </div>
        </div>
    </div>
</template>


<script>
    import * as types from '../../../store/action-types'
    import { WebCam } from "vue-web-cam";
    import { Notification } from 'element-ui';

    export default {
        store: store,
        components: {
            "vue-web-cam": WebCam
        },
        props: [
            'onChange',
            'personalImages',
            'person'
        ],
        data() {
            return {
                cameraStatus: true,
               img: {
                    front: null,
                    back: null
                },
                camera: null,
                deviceId: null,
                devices: [],
                side: 'front',
                printObj: {
                    id: "printMe",
                    popTitle: this.person ? this.person.fullName + ' ' + this.person.personal_number : 'პირადობა',
                    extraCss: 'h2{ display: none }',
                    extraHead: '<meta http-equiv="Content-Language"content="ka"/>'
                }
            };
        },
        computed: {
            device: function () {
                return this.devices.find(n => n.deviceId === this.deviceId);
            }
        },
        watch: {
            cameraStatus(){
                if (this.cameraStatus) {
                    this.onStart();
                } else {
                    this.onStop();
                }
            },
            camera: function (id) {
                this.deviceId = id;
            },
            devices: function () {
                // Once we have a list select the first one
                const [first, ...tail] = this.devices;
                if (first) {
                    this.camera = first.deviceId;
                    this.deviceId = first.deviceId;
                }
            },

        },
        created(){
            if (this.personalImages) {
                this.img = {
                    front: this.personalImages.front ? this.personalImages.front : null,
                    back: this.personalImages.back ? this.personalImages.back : null
                }
            }
        },
        methods: {
            onCapture() {
                this.img[this.side] = this.$refs.webcam.capture();
                this.onChange(this.img);
            },
            onStop() {
                this.$refs.webcam.stop();
            },
            onStart() {
                this.$refs.webcam.start();
            },
            onCameras(cameras) {
                this.devices = cameras;
            },
            onCameraChange(deviceId) {
                this.deviceId = deviceId;
                this.camera = deviceId;
            },
            onError(error) {
                Notification.error({
                    message: 'შეცდომა გადაღებისას, გთხოვთ სცადოთ თავიდან',
                    title: '',
                    offset: 100,
                })
            },
        }
    }
</script>
