const Item = require('../model/Item')
const Auth = require('../middleware/auth') 
exports.itemPostController = async(req,res) => {
    console.log(req.user)
    if(req.file){
        
    }
    console.log(req.body)
    // let imagess = req.file ? req.file.filename : null
    const image = req.file.filename;
    const url = req.protocol + '://' + req.get('host')
    
    try{
        const newItem = new Item({
            
            owner : req.user._id,
            ...req.body,
            image: url + '/public/uploads/productImage/' + req.file.filename, 
            


        })
        
        await newItem.save()
        res.status(201).send(newItem)
    } catch(error){
        console.log(error)
        res.status(400).send({success : false})
    }
} 
exports.singleItemGetController = async(req,res) => {
    console.log(req.params.name)
    try{
        const item = await Item.findOne({name: req.params.name})
        if(!item){
            res.status(404).send({error: "Item not found"})
        }
        console.log(item)
        res.status(200).json({success: true,item : item})
    }catch(error){
        res.status(400).send(error)
    }
}  
exports.singleItemsGetController = async(req,res) => {
    console.log(req.params.id)
    try{
        const item = await Item.findOne({_id: req.params.id})
        if(!item){
            res.status(404).send({error: "Item not found"})
        }
        console.log(item)
        res.status(200).json({success: true,item})
    }catch(error){
        res.status(400).send(error)
    }
}
exports.getAllItemController = async(req,res) => {
    try{
        const items = await Item.find({})
        res.status(200).send(items)
    }catch(error){
        res.status(400).send(error)
    }
}
exports.updateItemtController = async(req,res) => { 

try {
    const { id } = req.params;
    const { name, description, category, price, attributeType, attributes } = req.body;
    const imagePath = req.file ? req.file.path : null;

    const updatedItem = {
      name,
      description,
      category,
      price,
      attributeType,
      attributes,
    };

    if (imagePath) {
      updatedItem.image = imagePath;
    }

    await Item.findByIdAndUpdate(id, updatedItem);
    
    // Delete the uploaded file after it's used (if any)
    if (imagePath) {
      fs.unlinkSync(imagePath);
    }

    res.json({ success: true, message: 'Item updated successfully' });
  } catch (error) {
    console.error(error);
    res.status(500).json({ success: false, message: 'Internal Server Error' });
  }

}
exports.deleteItem = async(req,res) => {
    try {
        const deletedItem = await Item.findOneAndDelete( {_id: req.params.id} )
           if(!deletedItem) {
            res.status(404).send({error: "Item not found"})
        }
           res.send(deletedItem)
        } catch (error) {
           res.status(400).send(error)
        }
}