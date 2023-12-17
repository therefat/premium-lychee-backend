const Address = require("../model/Address")

exports.addressPostController = async(req,res) => { 
    const {isDefault,zip} = req.body 
   
    const owner = req.user._id
    try{ 
        const addressBook = await Address.findOne({owner}) 
        if(!addressBook){
            const newAddress = await Address.create({
                owner,
                address: [req.body]
            })

        }else {
            if (isDefault) {
               
            
                addressBook.address.forEach((addr) => (addr.isDefault = false));
              }
            addressBook.address.push(req.body) 
            await addressBook.save() 
            res.status(201).json({success : true,addressBook});
        }
        

    }catch (error) { 
        console.log(error)
        res.send({success : false,error : error})
    }
} 
exports.getAddressContoller = async(req,res) => {
    try{
        const owner = req.user._id; 
         const userAddress = await Address.findOne({owner}) 
         if (!userAddress) {
            res.status(404).json({ error: 'User not found' });
          } else {
            res.status(200).json(userAddress.address);
          }
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Internal Server Error' });
    }

} 

exports.addressDeleteController = async (req,res) => {
    const addressId = req.params.id 
    // console.log(id,"yy")
    try{ 
        const owner = req.user._id; 
        const userAddress = await Address.findOne({owner}) 
        // const deletedAddress = await Address.address.findByIdAndDelete(id)
       
        if (!userAddress) {
            res.status(404).json({ error: 'User not found' });
          } else {
            const addressToRemoveIndex = userAddress.address.findIndex(addr => addr._id.equals(addressId));
      
            if (addressToRemoveIndex === -1) {
              res.status(404).json({ error: 'Address not found' });
            } else {
              userAddress.address.splice(addressToRemoveIndex, 1);
      
              // If the removed address was set as default, update the new default
              if (userAddress.address.length > 0 && userAddress.address.every(addr => !addr.isDefault)) {
                userAddress.address[0].isDefault = true;
              }
      
              await userAddress.save();
              res.status(200).send({message : "deleted succesfully"});
            }
          }

    }catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Internal Server Error' });
    }
} 
exports.makeAddressDefault = async (req,res) => { 
  const addressId = req.params.id 
  try{
    const owner = req.user._id; 
    const userAddress = await Address.findOne({ owner });

    // Check if userAddress exists
    if (!userAddress) {
      return res.status(404).json({ error: 'Address not found' });
    }

    // Update all addresses for the owner to set isDefault to false
    await Address.updateMany({ owner }, { $set: { 'address.$[].isDefault': false } });


    const updatedAddress = await Address.findOneAndUpdate(
      { owner, 'address._id': addressId },
      { $set: { 'address.$.isDefault': true } },
      { new: true }
    );
    res.json(updatedAddress);
  } catch (error){
    console.error(error);
    res.status(500).json({ error: 'Internal Server Error' });
  }

} 
exports.getSingleAddress = async (req,res) => {
 const owner = req.user
  const addressId = req.params.id
  try{ 
    const userAddress = await Address.findOne({ owner });

    if (!userAddress) {
      return res.status(404).json({ error: 'User not found' });
    }

    
    const singleAddress = userAddress.address.find(addr => addr._id.equals(addressId));

    if (!singleAddress) {
      return res.status(404).json({ error: 'Address not found' });
    }

    res.json(singleAddress);
  }catch (error) {
    console.log(error)
    // console.error(error); 
    res.status(500).json({ error: 'Internal Server Error' });

  }
} 
exports.updatedAddressController = async (req,res) => { 
  const owner = req.user;
  const addressId = req.params.id;
  const {isDefault} = req.body
  const updateData = req.body; // As
  try{
    const userAddress = await Address.findOne({ owner });

    if (!userAddress) {
      return res.status(404).json({ error: 'User not found' });
    }

    // Find the index of the address to update
    const addressToUpdateIndex = userAddress.address.findIndex(addr => addr._id.equals(addressId));
    
    if (addressToUpdateIndex === -1) {
      return res.status(404).json({ error: 'Address not found' });
    }
    if(isDefault){
      await Address.updateMany({ owner }, { $set: { 'address.$[].isDefault': false } });
      userAddress.address[addressToUpdateIndex] = { ...userAddress.address[addressToUpdateIndex], ...updateData };
    }else{
      userAddress.address[addressToUpdateIndex] = { ...userAddress.address[addressToUpdateIndex], ...updateData };
    }
    // Update the address with the new data
    

    // Save the updated document
    await userAddress.save();

    res.json({ message: 'Address updated successfully' });
  } catch (error){
    console.error(error);
    res.status(500).json({ error: 'Internal Server Error' });
  }

} 