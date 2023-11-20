const User = require('../model/User')
const Auth = require('../middleware/auth')
exports.signupPostController = async (req,res) => {
    console.log(req.body)
    const user = new User(req.body)
    try {
        await user.save()
        const token = await user.generateAuthToken() 
        res.status(201).send({user,token})
        console.log('Accoutn created')
    } catch(error){
        // console.log(error)
        res.status(400).send(error)
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