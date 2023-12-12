const dotenv = require('dotenv');

dotenv.config()
const express = require('express')
const mongoose = require('mongoose')
const setRoutes = require('./routes/routes')
const cors = require('cors')
const app = express()
const port = 8080


// enabling cors for all routes 
app.use(cors())
// setting up cors headers
app.use((req, res,next) => {
  res.header('Access-Control-Allow-Origin', '*'); 
  res.header('Access-Control-Allow-Methods', 'GET, POST,PATCH, PUT, DELETE');
  res.header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
  next()
});
app.use(express.json())
app.use(express.urlencoded({extended: true}),)


app.use("/public", express.static("public"));



console.log(`Your port is ${process.env.PORT}`);
let PORT = process.env.PORT || 3000
//using  routes from Routes Directory
setRoutes(app)
app.listen(PORT,() => {
    console.log('App is running on port 8080}');
    mongoose.connect( 
        'mongodb://127.0.0.1:27017/premiumLychee', 
        {  useNewUrlParser: true } 
    ) 
        .then((data) => {
            console.log('DB Connected...');
        }) 
        .catch((err) => {
            console.log('Error: ' + err);
        })
   
})