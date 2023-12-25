const Item = require("../model/Item");
const Order = require("../model/Order");

exports.orderPostController = async (req,res) => {
    const owner = req.user._id; 
    console.log(req.body)
    
    try{
        const {user,shippingDetails,orderItems,status,shippingCost,bill,subTottal,paymentInfo,} = req.body
    const {orderNote} = req.body
    console.log(orderNote)
        const orders = new Order({
            owner : owner,
            user : user,
            shippingDetails : shippingDetails,
            orderItems : orderItems, 
            status : status,
            orderNote: orderNote,
            shippingCost : shippingCost,
            bill : bill,
            subTottal : subTottal,
          
            paymentInfo: paymentInfo,

          }); 
          await orders.save();

    res.status(201).json(orders);
    }catch (error) {
        console.error(error);
    res.status(500).send('Internal Server Error');

    }
} 
exports.userOrderController = async (req,res) => {
    let owner = req.user._id 
    try{

        const orders = await Order.find({owner : owner})
        if(!orders){
            return res.status(404).json({ error: 'Order not found' });
        }
        res.send(orders)

    } catch (error)  {
        console.error(error);
        res.status(500).send('Internal Server Error');
    }
} 
exports.getSingleOrderController = async(req,res) => {
    let owner = req.user._id 
    console.log(owner)
    
        try{
            const orderId = req.params.id 
        const orders = await Order.findById(orderId)
        if(!orders){
            return res.status(404).json({ error: 'Order not found' });
        } 
       
        res.json(orders)

    } catch (error)  {
        console.error(error);
        console.log(error)
        res.status(500).send('Internal Server Error');
    }
} 
exports.getAllOrder = async (req,res) => {
    try{
        const orders = await Order.find({})
        res.status(200).send(orders)
    } catch (error) {
        console.log(error)
    }
}
exports.orderStatus = async (req,res) =>{
   try{
    const {orderId} = req.params;
    const {status} = req.body; 
    const updateOrder = await Order.findByIdAndUpdate(orderId,{ status }, { new: true })
    if (!updatedOrder) {
        return res.status(404).json({ error: 'Order not found' });
      }
  
      res.json(updatedOrder);

   } catch (error) {
    console.error(error);
    res.status(500).send('Internal Server Error');
   }
}