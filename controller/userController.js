const User = require('../model/User')
const Auth = require('../middleware/auth')
const {validationResult} = require('express-validator')
exports.signupPostController = async (req,res) => {
    console.log(req.body)
    const user = new User(req.body)
    const errors = validationResult(req)
    try {
        if(!errors.isEmpty()){
            throw new Error('please give correct information')
        }
        await user.save()
        const token = await user.generateAuthToken() 
        res.status(201).send({user,token,errors: errors.array()})
        console.log('Accoutn created')
    } catch(e){
        // console.log(error.errors.password)
        console.log(e)
        res.status(400).send(e)
        
    }

}
exports.loginPostController = async (req,res) => {
    try{
        const user = await User.findByCredentials(req.body.email,req.body.password)
        if(!user){
            throw new Error('User Dose not match')
        }
        const token = await user.generateAuthToken()
        res.json({token : token,LoggedIn : true})
    } catch(Error){
        res.status(400).send(Error)
    }
}
exports.logoutController = async (req,res) => {
    try{
        req.user.tokens = req.user.tokens.filter((token) => {
            return token.token !== req.token
        })
        await req.user.save()
        res.send({LoggedIn : false})
        console.log('logout succeful')
    } catch(Error) {
        res.status(500).send(Error)
    }

}