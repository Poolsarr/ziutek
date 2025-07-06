# Ziutek

## Installation
```
npm install
```

## Development
Work directly with files in the main project directory.

Use this command for sass during development:
```
npx sass --watch .\scss\main.scss:style.css --style compressed
```
If you add any new html/js files remember to add them to build script in `package.json`
## Production Build
```
npm run build
```
Deploy files from `/optimized` directory. Note: Assets won't be copied to this directory.