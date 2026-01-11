import { defineConfig } from 'astro/config';

// https://astro.build/config
export default defineConfig({
  site: 'https://vremyacheloveka.ru',
  output: 'static',
  build: {
    assets: '_astro'
  },
  vite: {
    build: {
      cssCodeSplit: false
    }
  }
});