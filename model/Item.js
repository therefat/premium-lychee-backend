const mongoose = require('mongoose')
const ObjectId = mongoose.Schema.Types.ObjectId
const itemSchema = new mongoose.Schema({
    owner: {
        type: ObjectId,
        required: true,
        ref: 'User'
    },
    name: {
        type: String,
        required: true,
        trim: true
    },
    description: {
        type: String,
        required: true
    },
    category: {
        type: String,
        required: true
    },
    price: {
        type : Number, 
        required: true
    },
    image : {
        type: String,
        required : true
    },
    attributes : [
        {
            attribute_quantity  : {
                type : String
            },
            attribute_price: {
                type :String
            }
        }
    ]
},{
    timestamps: true
})
const Item = mongoose.model('Item',itemSchema)
module.exports = Item