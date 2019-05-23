const path = require('path')

module.exports = {
    mode: 'development',
    entry: './template/js/scheduler/scheduler.js',
    resolve: {
        extensions: [ '.js' ]
    },
    output: {
        filename: 'scheduler.js',
        path: path.join(__dirname, 'template/js/scheduler/dist')
    },
    devtool: 'sourcemap'
}