const { getAllItemController } = require('../controller/ItemController')
const { orderPostController, userOrderController, getSingleOrderController, getAllOrder, orderStatus } = require('../controller/orderController')
const auth = require('../middleware/auth')
const isAdmin = require('../middleware/isAdmin')

const router = require('express').Router() 
router.post('/newoders',auth,orderPostController) 
router.get('/corder',auth,userOrderController)
router.get('/singleorder/:id',auth,getSingleOrderController)
router.get('/allorder',auth,isAdmin,getAllOrder)
router.patch('orderStatus',auth,isAdmin,orderStatus)
module.exports = router