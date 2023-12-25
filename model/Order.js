const mongoose = require('mongoose');
const ObjectId = mongoose.Schema.Types.ObjectId;

const orderSchema = new mongoose.Schema({

    owner : {
        type: ObjectId,
        required : true,
        ref : 'User'
    },
    user : {
        name: String,
        email: String,
    },
    
    shippingDetails: { 
        name: String,
        email: String,
        phone: String,
        city: String,
        upzila: String,
        address: String,
        zip: String
    },
    orderItems: [
        {
            id: {
                type: ObjectId
            },
            itemsId: {
                type: ObjectId
            },
            name: {  
                type: String
            },
            price: {
                type: Number
            },image: {
                type : String
            },
            quantity: { 
                type: Number
            }
        }
    ],
    status: {
        type: String,
        enum: ['Pending', 'Approve','Processing', 'Courier','Shipped', 'Delivered'],
        default: 'Pending',
      },
      orderNote : String,
    subTottal : Number,
    shippingCost: Number,
    bill: Number,
    paymentInfo : {
        paymentMethod: {
            type : String
        },
        transactionid : {
            type : String
        },
        accountNo : String
    }, 
    
    
}, 

{
    timestamps: true
});

const Order = mongoose.model('Order', orderSchema);
module.exports = Order;