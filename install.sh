# JS
cat "../extensions/build/extensions.js" > "./build/index.js"
cat "./imports.js"                     >> "./build/index.js"
cat "./country_base.js"                >> "./build/index.js"
