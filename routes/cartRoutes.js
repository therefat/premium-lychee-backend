const { postCart, getCart, cartDelete } = require('../controller/cartController')
const auth = require('../middleware/auth')

const router = require('express').Router() 

router.post('/addcart',auth,postCart)
router.get('/getcart',auth,getCart) 
router.delete('/deletcart',auth,cartDelete) 
module.exports = router