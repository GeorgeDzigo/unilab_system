<template>
    <el-button
        :loading="loading"
        :disabled="loading"
        @click="scan"
        icon="el-icon-search"
        type="warning">
        {{ !loading ? 'სკანირება' : 'მიმდინარეობს სკანირება' }}
    </el-button>
</template>

<script>
    import * as types from '../../store/action-types'
    import {getData} from '../../mixins/getData'
    import {responseParse} from '../../mixins/responseParse'
    import { Notification } from 'element-ui';


    export default {
        store: store,
        props: [
            'routes',
            'callBackFunction'
        ],

        data(){
            return {
                loading: false
            }
        },

        methods: {

            async scan(){

                this.loading = true;

                await getData({
                    method: 'POST',
                    url: this.routes.scanner,
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

                        this.$store.dispatch(types.CARD_ID, data.cardId);

                        this.callBackFunction(data.cardId);

                    }

                    this.loading = false;

                })

            }

        },

    }

</script>
