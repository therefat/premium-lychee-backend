const mongoose = require('mongoose');
const ObjectId = mongoose.Schema.Types.ObjectId;

const orderSchema = new mongoose.Schema({
    
    name: String,
    email: String,
    shipping_details: {
        name: String,
        email: String,
        phone: String,
        city: String,
        upzilla: String,
        address: String,
        zip: String
    },
    cartItems: [
        {
            id: {
                type: ObjectId
            },
            itemsId: {
                type: ObjectId
            },
            itemsName: {  
                type: String
            },
            itemsPrice: {
                type: Number
            },
            qunatitys: { 
                type: String
            }
        }
    ],
    orderNote : String,
    shipping_cost: Number,
    bill: Number
}, {
    timestamps: true
});

const Order = mongoose.model('Order', orderSchema);
module.exports = Order;