const jwt = require('jsonwebtoken')
const User = require('../model/User')
const auth = async(req,res,next) => {
    // console.log(req.header('Authorization'))
    try{
        const token = req.header('Authorization').replace('Bearer','') 
        // console.log(token)
        console.log(process.env.JWT_SECRET)
        const decoded = await jwt.verify(token,process.env.JWT_SECRET)
        
        const user = await User.findOne({_id: decoded.data._id,'tokens.token': token})
      
        if(!user){
            throw new Error
        }
        req.token = token
        req.user = user
        res.send({succes : true})
    } catch(error){
        res.status(401).send({error: "Authentication required"})

    }
}
module.exports = auth