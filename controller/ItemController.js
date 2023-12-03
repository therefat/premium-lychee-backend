const Item = require('../model/Item')
const Auth = require('../middleware/auth') 
exports.itemPostController = async(req,res) => {
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
        res.status(400).send({})
    }
} 
exports.singleItemGetController = async(req,res) => {
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
exports.getAllItemController = async(req,res) => {
    try{
        const items = await Item.find({})
        res.status(200).send(items)
    }catch(error){
        res.status(400).send(error)
    }
}
exports.updateItemtController = async(req,res) => {
    const updates = Object.keys(req.body)
    const url = req.protocol + '://' + req.get('host')
    const allowedUpdates = ['name','description','category','price']
    const isValidOperation = updates.every((update) =>    allowedUpdates.includes(update))
   if(!isValidOperation) {
     return res.status(400).send({ error: 'invalid updates'})
}
try{
    const item = await Item.findOne({_id: req.params.id})
    if(!item){
        return res.status(404).send()
    }
    updates.forEach((update) => item[update] = req.body[update])
    if (req.file) {
        // If there is an uploaded image, update the image filename
        item.image = url + '/public/uploads/productImage/' + req.file.filename
      }
    await item.save()
    res.send(item)
} catch(error){
    res.status(400).send(error)
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