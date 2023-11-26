const {body} = require('express-validator')
const User = require('../../model/User')
module.exports = [
    body('name')
        .isLength({min : 2,max:15})
        .withMessage('Username Must Be Between 2 to 15 chars')
        
        .trim(),
    body('email')
        .isEmail().withMessage('Please Provide a valid Email')
        .custom(async email => {
            let user = await User.findOne({email})
            if(user){
                return Promise.reject('Email Already in Use')
            }
        })
        .normalizeEmail(),
    body('password')
    .isLength({min: 6}).withMessage('Your Password Must Be Greater Than 6 chars'),
]