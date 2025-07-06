const { PurgeCSS } = require('purgecss');
const fs = require('fs');
const path = require('path');

// Import the configuration
const purgeConfig = require('./purgecss.config.cjs');

(async () => {
  try {
    console.log('Running PurgeCSS...');
    
    // The config file specifies the content and css files.
    const results = await new PurgeCSS().purge(purgeConfig);

    if (results.length > 0 && results[0].css) {
      const outputPath = path.join(__dirname, 'optimized', 'style.css');
      fs.writeFileSync(outputPath, results[0].css);
      console.log('PurgeCSS finished successfully. Stylesheet saved to:', outputPath);
    } else {
      console.log('PurgeCSS finished, but no CSS was generated. Check your config.');
    }
  } catch (error) {
    console.error('Error during PurgeCSS execution:', error);
    process.exit(1); // Exit with an error code
  }
})();