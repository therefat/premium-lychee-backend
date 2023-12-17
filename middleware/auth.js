const jwt = require('jsonwebtoken')
const User = require('../model/User')
const auth = async (req,res,next) => {
    // console.log(req.header('Authorization'))
    try{
      
            const authorizationHeader = req.header('Authorization');
            
            if (!authorizationHeader) {
                throw new Error('Authorization header is missing');
            }
        const token = req.header('Authorization').replace('Bearer','') 
      
     
        
        
       
        const decoded = await jwt.verify(token,process.env.JWT_SECRET)
    //    console.log(decoded)
        
        const user = await User.findOne({_id: decoded.data._id,}) 
        // 'tokens.token' : token}
       
     
        if(!user){
            throw new Error
        }
        req.token = token
        req.user = user
      next()
       
    } catch(error){
        console.log(error)
        res.status(401).send({error: "Authentication required"}) 
      

    }
}

module.exports = auth
