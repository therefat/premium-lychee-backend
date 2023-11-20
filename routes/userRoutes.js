const router = require('express').Router() 
const Auth = require('../middleware/auth')
const {
    signupPostController,
    loginPostController,
    logoutController
} = require('../controller/userController')
router.post('/signup',signupPostController)
router.post('/login',loginPostController)
router.post('/logout',Auth,logoutController)

module.exports = router