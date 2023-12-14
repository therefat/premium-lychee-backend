const express = require('express') 
const Cart = require('../model/Cart') 
const Item = require('../model/Item') 
exports.getCart = async(req,res) => {
    const owner = req.user._id;

  try {
    const cart = await Cart.findOne({ owner });
    if (cart && cart.items.length > 0) {
      res.status(200).send(cart);
    } else {
      res.send(null);
    }
  } catch (error) {
    res.status(500).send();
  }
} 
exports.postCart = async(req,res) => {
    const owner = req.user._id;
  const { itemId, quantity,attribute_price  } = req.body; 
  

  try {
    const cart = await Cart.findOne({ owner });
    const item = await Item.findOne({ _id: itemId }); 
    console.log(item)

    if (!item) {
      res.status(404).send({ message: "item not found" });
      return;
    } 
    let selectedAttribute; 
    if(item.attributes && attribute_price){
      selectedAttribute = item.attributes.find(attr => attr.attribute_price === attribute_price); 
      if (!selectedAttribute) {
        return res.status(400).json({ error: 'Selected varient not available for this item' });
    } 
    }else { 
      console.log('test')
      selectedAttribute = {
        attribute_price: item.price, 
        attribute_quantity : item.quantity,
        attribute_type: 'Default',
    };
    }
    console.log(selectedAttribute)
    const price = selectedAttribute.attribute_price;
    const name = item.name;
    const image = item.image 
    console.log(image)
    //If cart already exists for user,
    if (cart) {
      const itemIndex = cart.items.findIndex((item) => item.itemId == itemId);
      //check if product exists or not

      if (itemIndex > -1) {
        let product = cart.items[itemIndex];
        product.quantity += quantity;

        cart.bill = cart.items.reduce((acc, curr) => {
            return acc + curr.quantity * curr.price;
        },0)
        
        cart.items[itemIndex] = product;
        await cart.save();
        res.status(200).send(cart);
      } else {
        cart.items.push({ itemId, name,image, quantity, price });
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
exports.cartDelete = async(req,res) => {
    const owner = req.user._id;
    const itemId = req.query.itemId; 
    console.log(itemId,'test')
     try {
       let cart = await Cart.findOne({ owner });
   
       const itemIndex = cart.items.findIndex((item) => item.itemId == itemId);
       
       if (itemIndex > -1) {
         let item = cart.items[itemIndex];
         cart.bill -= item.quantity * item.price;
         if(cart.bill < 0) {
             cart.bill = 0
         } 
         cart.items.splice(itemIndex, 1);
         cart.bill = cart.items.reduce((acc, curr) => {
           return acc + curr.quantity * curr.price;
       },0)
         cart = await cart.save();
   
         res.status(200).send(cart);
       } else {
       res.status(404).send("item not found");
       }
     } catch (error) {
       console.log(error);
       res.status(400).send();
     }
}