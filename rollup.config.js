const resolve = require('@rollup/plugin-node-resolve');
const commonjs = require('@rollup/plugin-commonjs');
const typescript = require('@rollup/plugin-typescript');
const postcss = require('rollup-plugin-postcss');
const autoprefixer = require('autoprefixer');
const tailwindcss = require('tailwindcss');

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
        postcss({
            extract: true,
            extensions: ['.css'],
            plugins: [tailwindcss, autoprefixer],
        }),
    ],
};