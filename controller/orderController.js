
const Order = require("../model/Order");

// Controller to handle the creation of a new order
exports.orderPostController = async (req,res) => {
      // Extract the owner ID from the authenticated user
    const owner = req.user._id; 
    
    
    try{ 
         // Destructure request body to extract relevant order information
        const {user,shippingDetails,orderItems,status,shippingCost,bill,subTottal,paymentInfo,} = req.body
    const {orderNote} = req.body
    // Create a new Order instance with the extracted information
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
            // Save the new order to the database
          await orders.save();
        // Respond with the created order
    res.status(201).json(orders);
    }catch (error) {
        console.error(error);
    res.status(500).send('Internal Server Error');

    }
} 
// Controller to retrieve orders for a specific user
exports.userOrderController = async (req,res) => {
    // Extract the owner ID from the authenticated user
    let owner = req.user._id 
    try{
        // Find orders associated with the user
        const orders = await Order.find({owner : owner})
        // If no orders are found, return a 404 error
        if(!orders){
            return res.status(404).json({ error: 'Order not found' });
        }
        // Respond with the user's orders
        res.send(orders)

    } catch (error)  {
        console.error(error);
        res.status(500).send('Internal Server Error');
    }
} 
// Controller to retrieve a single order for a specific user
exports.getSingleOrderController = async(req,res) => {
     // Extract the owner ID from the authenticated user
    let owner = req.user._id 
    
    
        try{
            // Extract the order ID from the request parameters
            const orderId = req.params.id 
        // Find the specific order by ID
        const orders = await Order.findById(orderId) 
         // If the order is not found, return a 404 error

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
// Controller to retrieve all orders
exports.getAllOrder = async (req,res) => {
    try{
         // Find all orders in the database
        const orders = await Order.find({})
        res.status(200).send(orders)
    } catch (error) {
        console.log(error)
    }
}
// Controller to update the status of a specific order
exports.orderStatus = async (req,res) =>{
 
   try{
       // Extract order ID and status from request parameters and body
    const {orderId} = req.params;
    const {status} = req.body; 
    // Update the order status in the database
    const updatedOrder = await Order.findByIdAndUpdate(orderId,{ status }, { new: true })
    // If the order is not found, return a 404 error
    if (!updatedOrder) {
        return res.status(404).json({ error: 'Order not found' });
      }
  
      res.json(updatedOrder);

   } catch (error) {
    console.error(error);
    res.status(500).send('Internal Server Error');
   }
}