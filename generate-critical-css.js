const fs = require('fs');
const path = require('path');

// Define the pages you want to generate critical CSS for
const pages = [
  {
    url: 'index.html',
    template: 'index.html'
  },
  {
    url: 'cennik.html',
    template: 'cennik.html'
  },
  {
    url: 'galeria.html',
    template: 'galeria.html'
  }
];

// The source of your full, compiled CSS file
const compiledCssPath = path.join(__dirname, 'style.css');

// A function to run the critical process
(async () => {
  try {
    const { generate } = await import('critical');
    console.log('Starting critical CSS generation...');

    // Ensure the source CSS file exists
    if (!fs.existsSync(compiledCssPath)) {
      throw new Error(`Source CSS file not found at: ${compiledCssPath}. Please compile your SCSS first.`);
    }

    for (const page of pages) {
      console.log(`Processing: ${page.url}`);

      const { html } = await generate({
        // The path to your source HTML file
        src: page.url,

        // The path to your full compiled CSS file
        css: [compiledCssPath],

        // Set the viewport dimensions for "above-the-fold"
        // dimensions: [
        //   {
        //     height: 823,
        //     width: 412,
        //   },
        //   {
        //     height: 900,
        //     width: 1200,
        //   },
        // ],
        height: 823,
        width: 412,
        
        // Set to true to inline the generated critical CSS
        inline: true,

        // Do not extract the remaining "uncritical" CSS into a separate file.
        // The original stylesheet will be loaded asynchronously.
        extract: false,

        // https://github.com/clean-css/clean-css
        cleanCSS: {level: {  1: { all: true }, 2: { all: true} }}
      });

      // Define the path for the optimized file
      const outputPath = path.join(__dirname, 'optimized', page.template);

      // Ensure the 'optimized' directory exists
      fs.mkdirSync(path.dirname(outputPath), { recursive: true });

      // Write the optimized HTML to the new file
      fs.writeFileSync(outputPath, html);
      console.log(`Successfully generated critical HTML for ${page.template} at ${outputPath}`);
    }

    console.log('Critical CSS generation finished successfully!');
  } catch (error) {
    console.error('Error during critical CSS generation:', error);
  }
})();