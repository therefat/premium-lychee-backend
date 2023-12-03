const router = require('express').Router() 
const Auth = require('../middleware/auth')
// const signupValidator = require('../middleware/signupValidator')
const {
    signupPostController,
    loginPostController,
    logoutController
} = require('../controller/userController')
const signupValidator = require('../validator/auth/signupValidator.')
const loginValidator = require('../validator/auth/loginValidator')
router.post('/signup',signupValidator,signupPostController)
router.post('/login',loginValidator,loginPostController)
router.post('/logout',Auth,logoutController)

module.exports = router