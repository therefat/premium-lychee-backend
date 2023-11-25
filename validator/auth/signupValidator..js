const {body} = require('express-validator')
const User = require('../../model/User')
module.exports = [
    body('password')
    .isLength({min: 7}).withMessage('Your Password Must Be Greater Than 5 chars'),
]