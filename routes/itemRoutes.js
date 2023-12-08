const { itemPostController, singleItemGetController, getAllItemController, updateItemtController, deleteItem } = require('../controller/ItemController')

const auth = require('../middleware/auth')
const isAdmin = require('../middleware/isAdmin')
const upload = require('../middleware/uploadMiddleware')

const router = require('express').Router() 

router.post('/addItems',auth,isAdmin,upload.single('image'),itemPostController) 
router.get('/allItems',getAllItemController),
router.get('/singleItem/:name',singleItemGetController)
router.patch('/updateItems/:id',auth,updateItemtController) 
router.delete('/deletItem/:id',auth,deleteItem)
module.exports = router 