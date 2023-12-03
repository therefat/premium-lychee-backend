const User = require('../model/User')
const Auth = require('../middleware/auth')
const errorFormatter = require('../utils/validationErrorFormater')
const {validationResult} = require('express-validator')
exports.signupPostController = async (req,res) => {
    console.log(req.body)
    const errors = validationResult(req)
    
    
    try {
        
    if(!errors.isEmpty()){
        return res.status(422).json({ errors: errors.formatWith(errorFormatter).mapped() })
    }
    const user = new User(req.body)
        await user.save()
        const token = await user.generateAuthToken() 
        res.status(201).send({user,token})
        console.log('Accoutn created')
    } catch(e){
        // console.log(error.errors.password)
        
        res.status(400).send(e)
        
    }

}
exports.loginPostController = async (req,res) => {
    const errors = validationResult(req)
    try{
        if(!errors.isEmpty()){
            return res.status(422).json({ errors: errors.formatWith(errorFormatter).mapped() })
        }
        const user = await User.findByCredentials(req.body.email,req.body.password)
        if(!user){
            throw new Error('User Dose not match')
        }
        const token = await user.generateAuthToken()
        // res.cookie('token', token, { httpOnly: true });  
        // res.cookie('token', token, { httpOnly: true, expires: new Date(Date.now() + 24 * 60 * 60 * 1000) });
        res.cookie('token', token, { httpOnly: true, path: '/' });
        res.json({token : token,LoggedIn : true})
    } catch(error){
        res.status(400).send({ errors: error.message })
        
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
    } catch(error) {
        res.status(500).send(error)
    }

}