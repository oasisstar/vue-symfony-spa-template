const path = require("path");
const Dotenv = require("dotenv-webpack");
const PrerenderSPAPlugin = require("prerender-spa-plugin");

module.exports = {
  configureWebpack: {
    plugins: [
      new Dotenv({
        path: path.join(__dirname, ".env"),
        safe: true,
        systemvars: true,
        silent: false
      }),
      new PrerenderSPAPlugin({
        staticDir: path.join(__dirname, "dist"),
        routes: ["/", "/about", "/login", "/register", "/resseting"]
      })
    ]
  }
};
