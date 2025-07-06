import { nodeResolve } from '@rollup/plugin-node-resolve';
import terser from '@rollup/plugin-terser';

export default {
  input: 'scripts/main.js',
  output: {
    file: 'scripts/bundle.js',
    format: 'iife',
    sourcemap: false
  },
  plugins: [
    nodeResolve(),
    terser({
      compress: true,
      mangle: true,
      format: {
        comments: false
      }
    })
  ]
};