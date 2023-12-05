const mongoose = require('mongoose');
const validator = require('validator')
// const bcrypt = require('bcryptjs')
const bcrypt = require('bcryptjs')
const jwt = require('jsonwebtoken')
const userSchema = new mongoose.Schema({
  name: {
    type: String,
    required: true,
    trim: true,
    lowercase: true,
  },
  email : {
    type : String,
    required : true,
    
    lowercase : true,
    unique : true,
    
  },
  password: {
    type : String,
    required : true,
    
    trim : true,
   
  },
  tokens : [{
    token : {
        type : String,
        required : true
    }
  }]
},{
  timestamps: true 
});

//Generate auth token
userSchema.methods.generateAuthToken = async function () {
  const user = this 
  const userData = {
    name : user.name,
    email : user.email,
    _id : user._id
  }
  const token = jwt.sign({data : userData},
  process.env.JWT_SECRET,{
      expiresIn: '2d'
  }) 
  user.tokens = user.tokens.concat({token})
  await user.save()
  return token
}

//login in users
userSchema.statics.findByCredentials = async (email, password) => {
  const user = await User.findOne({ email })  // U line
  if (!user) {
      throw new Error('Please Provide valide Credential')
      
  }
  const isMatch = await bcrypt.compare(password, user.password)
  console.log(isMatch)
  if(!isMatch) {
      throw new Error('Please Provide valide Credential') 
      
  }

  return user
}
//Hash plain password before saving
userSchema.pre('save',async function(next){
  const user = this 
  if(user.isModified('password')){
      user.password = await bcrypt.hash(user.password,8)
  }
  next()
})
const User = mongoose.model('User', userSchema)
module.exports = User;




















