const webpack = require('webpack');
const path = require('path');

const config = {
    mode: 'production',
    entry: './build/entry.js',
    output: {
        path: path.resolve(__dirname, 'dist'),
        filename: 'app.js'
    },
    module: {
        rules: [
            {
                test: /\.js?$/,
                exclude: [/node_modules/],
            },
        ]
    },
};

module.exports = config;