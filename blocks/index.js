// Automatically import all block.js files from subfolders
const requireContext = require.context('./', true, /\/block\.js$/);
requireContext.keys().forEach(requireContext);