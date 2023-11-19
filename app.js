const dotenv = require('dotenv');

dotenv.config()
const express = require('express')
const mongoose = require('mongoose')
const app = express()
const port = 8080

app.use(express.json())
app.get('/',(req,res) => {
    res.send('Hellow World')
})
console.log(`Your port is ${process.env.PORT}`);
let PORT = process.env.PORT || 3000

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