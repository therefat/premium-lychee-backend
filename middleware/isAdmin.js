const isAdmin = (req, res, next) => {
    // Assuming that the user object is available in req.user (set by the auth middleware)
    const user = req.user;

    // Check if the user has the 'admin' role
    if (user && user.role === 'admin') {
        next();
    } else {
        res.status(403).send({ error: 'Admin access required' });
    }
};

module.exports = isAdmin;