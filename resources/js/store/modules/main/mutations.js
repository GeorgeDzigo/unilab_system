import * as types from '../../mutation-types'

export default {

    [ types.CARD_ID ] (state, cardId) {
        // Set cardId data
        state.cardId = cardId
    },

    [ types.PERSONAL_CARD_IMAGES ] (state, personalCardImages) {
        // Set Personal card images data
        state.personalCardImages = personalCardImages
    }


}
