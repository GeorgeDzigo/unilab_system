import * as actionTypes from '../../action-types'
import * as mutationTypes from '../../mutation-types'

import { getData } from '../../../mixins/getData'

export default {

    /**
     * Commit CARD_ID data
     *
     * @param context
     * @param data
     */
    [ actionTypes.CARD_ID ] (context, data) {
        context.commit(mutationTypes.CARD_ID, data)
    },

    /**
     *
     * @param context
     * @param data
     */
    [ actionTypes.PERSONAL_CARD_IMAGES ] (context, data) {
        context.commit(mutationTypes.PERSONAL_CARD_IMAGES, data)
    },


}
