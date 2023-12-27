
const Cart = require('../model/Cart') 
const Item = require('../model/Item') 

// Controller to get the user's cart
exports.getCart = async(req,res) => {
    const owner = req.user._id;

  try {
      // Find the user's cart
    const cart = await Cart.findOne({ owner });
     // Check if the cart exists and has items
    if (cart && cart.items.length > 0) {
      res.status(200).send(cart);
    } else {
      res.send(null);
    }
  } catch (error) {
    res.status(500).send();
  }
} 
// Controller to add items to the user's cart
exports.postCart = async(req,res) => {
    const owner = req.user._id;
  const { itemId, quantity,attribute_price  } = req.body; 
  

  try {
     // Find the user's cart
    const cart = await Cart.findOne({ owner });
    // Find the item by ID
    const item = await Item.findOne({ _id: itemId }); 
   
     // Check if the item exists
    if (!item) {
      res.status(404).send({ message: "item not found" });
      return;
    } 
    let selectedAttribute; 
     // Check if the item has attributes and an attribute_price is provided
   
    if(item.attributes && attribute_price){ 

      // Find the selected attribute
      selectedAttribute = item.attributes.find(attr => attr.attribute_price === attribute_price); 
      if (!selectedAttribute) {
        return res.status(400).json({ error: 'Selected varient not available for this item' });
    } 
    }else { 
      // If no attributes or attribute_price provided, use default values
      selectedAttribute = {
        attribute_price: item.price, 
        attribute_quantity : item.quantity,
        attribute_type: 'Default',
    };
    }
   
    const price = selectedAttribute.attribute_price;
    const name = item.name;
    const image = item.image 
   
    //If cart already exists for user,
    if (cart) {
      const itemIndex = cart.items.findIndex((item) => item.itemId == itemId);
      //check if product exists or not
      
      if (itemIndex > -1) {
        let product = cart.items[itemIndex];
        product.quantity = Number(product.quantity) + Number(quantity);
        // Update the cart bill based on the updated quantity
        cart.bill = cart.items.reduce((acc, curr) => {
            return acc + curr.quantity * curr.price;
        },0)
        
        cart.items[itemIndex] = product;
        await cart.save();
        res.status(200).send(cart);
      } else {
        // Add the new item to the cart
        cart.items.push({ itemId, name,image, quantity, price });
         // Update the cart bill based on the new item
        cart.bill = cart.items.reduce((acc, curr) => {
            return acc + curr.quantity * curr.price;
        },0)

        await cart.save();
        res.status(200).send(cart);
      }
    } else {
      //no cart exists, create one
      const newCart = await Cart.create({
        owner,
        items: [{ itemId, name,image, quantity, price }],
        bill: quantity * price,
      });
      return res.status(201).send(newCart);
    }
  } catch (error) {
    console.log(error);
    res.status(500).send("something went wrong");
  }
} 
// Controller to remove an item from the user's cart
exports.cartDelete = async(req,res) => {
    const owner = req.user._id;
    const itemId = req.query.itemId; 
  
     try {
       // Find the user's cart
       let cart = await Cart.findOne({ owner });
       // Find the index of the item in the cart
   
       const itemIndex = cart.items.findIndex((item) => item.itemId == itemId);
       // If the item is found, remove it from the cart
       if (itemIndex > -1) {
         let item = cart.items[itemIndex];
         cart.bill -= item.quantity * item.price;
          // Ensure the cart bill does not go below zero
         if(cart.bill < 0) {
             cart.bill = 0
         } 
         cart.items.splice(itemIndex, 1);
         // Update the cart bill based on the removed item
         cart.bill = cart.items.reduce((acc, curr) => {
           return acc + curr.quantity * curr.price;
       },0)
       // Save the updated cart
         cart = await cart.save();
   
         res.status(200).send(cart);
       } else {
        // If the item is not found, return a 404 error
       res.status(404).send("item not found");
       }
     } catch (error) {
       console.log(error);
       res.status(400).send();
     }
}