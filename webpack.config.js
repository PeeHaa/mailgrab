const path = require('path');
const webpack = require('webpack');
const WebpackBuildNotifierPlugin = require('webpack-build-notifier');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
    entry: {
        'bundle': './assets/js/main.js',
        'bundle.min': './assets/js/main.js'
    },
    devtool: "source-map",
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'public/js')
    },
    plugins: [
        new webpack.DefinePlugin({
            DEBUG: JSON.stringify(true)
        }),
        new webpack.optimize.UglifyJsPlugin({
            include: /\.min\.js$/,
            minimize: true
        }),
        new WebpackBuildNotifierPlugin({
            title: 'MailGrab',
            successSound: false
        }),
        new ExtractTextPlugin({
            filename: "/../css/[name].css"
        })
    ],
    watch: true,
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['./../../node_modules/babel-preset-env']
                    }
                }
            },
            {
                test: /\.scss|\.css/,
                use: ExtractTextPlugin.extract({
                    use: ["css-loader", "sass-loader"]
                })
            }
        ]
    },
    node: {
        fs: 'empty'
    }
};
