const resolve = require('@rollup/plugin-node-resolve');
const commonjs = require('@rollup/plugin-commonjs');
const typescript = require('@rollup/plugin-typescript');
const sass = require('rollup-plugin-sass');

module.exports = {
    input: 'assets/src/controller.ts',
    output: {
        file: 'assets/dist/controller.js',
        format: 'esm',
    },
    external:[
        '@hotwired/stimulus',
    ],
    plugins: [
        resolve(),
        typescript(),
        commonjs(),
        sass({
            output: 'assets/dist/styles.min.css',
            options: {
                outputStyle: 'compressed',
            },
        }),
    ],
};