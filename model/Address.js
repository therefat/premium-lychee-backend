const mongoose = require('mongoose');
const ObjectId = mongoose.Schema.Types.ObjectId;
const addressSchema = new mongoose.Schema({
    owner : {
        type: ObjectId,
        required: true,
        ref: 'User'
    },
    address : [{
        name : {
            type: String
        },
        email : {
            type : String
        },
        phone: {
            type : String
        },
        city : {
            type: String
        },
        upzila : {
            type : String
        }, 
        address : {
            type: String
        },
        orderNote : {
            type : String
        },
        zip : {
            type : String
        },
        isDefault: Boolean,
    }

    ]
    
}) 
const Address = mongoose.model('Address',addressSchema) 
module.exports = Address