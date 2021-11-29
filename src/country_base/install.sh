# Generate JS
cat "./vendor/extensions.js"    > "./build/index.js"
cat "./src/js/imports.js"      >> "./build/index.js"
cat "./src/js/country_base.js" >> "./build/index.js"
