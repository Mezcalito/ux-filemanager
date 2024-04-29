const resolve = require('@rollup/plugin-node-resolve');
const commonjs = require('@rollup/plugin-commonjs');
const typescript = require('@rollup/plugin-typescript');
const scss = require('rollup-plugin-scss');

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
        typescript({
            filterRoot: 'assets',
            include: ['assets/*.ts'],
            compilerOptions: {
                outDir: 'dist',
                declaration: true,
                emitDeclarationOnly: true,
            }
        }),
        commonjs(),
        scss({
            fileName: 'styles.css',
            watch: ['assets/src/scss', 'assets/src/scss/styles.scss'],
        }),
    ],
};