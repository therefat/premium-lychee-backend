
const { addressPostController, getAddressContoller, addressDeleteController, makeAddressDefault, getSingleAddress, updatedAddressController, defaultAddressController } = require('../controller/addressController')
const auth = require('../middleware/auth')

const router = require('express').Router()  

router.get('/getaddress',auth,getAddressContoller)
router.post('/addaddress',auth,addressPostController) 
router.delete('/deleteaddress/:id',auth,addressDeleteController) 
router.put('/updatedefault/:id',auth,makeAddressDefault) 
router.get('/getsingleAddress/:id',auth,getSingleAddress)
router.put('/updateaddress/:id',auth,updatedAddressController)
router.get('/defultaddress',auth,defaultAddressController)

module.exports = router