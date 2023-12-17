const userRoutes = require('./userRoutes')
const itemRoutes = require('./itemRoutes') 
const cartRoutes = require('./cartRoutes') 
const addressRoutes = require('./addressRoutes')
const routes = [
    {
        path : "/auth",
        handler : userRoutes
    },{
        path : "/items",
        handler : itemRoutes
    },{
        path : '/cart',
        handler : cartRoutes
    },{
        path : '/address',
        handler : addressRoutes
    },{
        path: '/',
        handler : (req,res) =>{
            res.json({
                message :"Hello Wrold"
            })
        }
    }
]
module.exports = app => {
    routes.forEach(r =>{
        if(r.path == '/'){
            app.get(r.path,r.handler)
        }else{
            app.use(r.path,r.handler)
        }
    })
}