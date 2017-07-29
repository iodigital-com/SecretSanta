# Assets

We use symfony Webpack Encore to manage our assets. See the [symfony documentation](http://symfony.com/doc/current/frontend.html) for more information about encore.

## Local development

First install all necessary dependencies by executing the following command in the root dir.

```bash
yarn
```

To develop asset changes locally you can run

```bash
./node_modules/.bin/encore dev
```

Or this command to watch the asset files for changes

```bash
./node_modules/.bin/encore dev --watch
```

When you are done with changes in the assets files, simply run in production mode and commit these generated files.

```bash
./node_modules/.bin/encore production
```
