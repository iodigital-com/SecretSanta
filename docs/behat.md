# Behat

## Running tests

This test suite uses google chrome to run the behat features.
The latest version of google-chrome-stable and chromedriver are installed if your vagrant box has been provisioned recently. 

These binaries could need a manual update if you want to use the most recent version. This is because it is only updated/installed when the vagrant box is provisioned.

The chromedriver is already in a location included in PATH.

To start the tests first run an instance of selenium:

### Setup

```bash
vendor/bin/selenium-server-standalone
```

Make sure the symfony local server is started (with the test environment):

```bash
symfony server:ca:install
APP_ENV=test symfony server:start -d
```

Here we run it as a daemon (`-d`), this is not necessary. But firstly we'll a local certificate authority for the Symfony local webserver so content can be server over HTTPS. 

### Run
Now everything is set up to run behat tests, these can be run simply by running:
```bash
vendor/bin/behat
```

Or if you want to execute a specific test:
```bash
vendor/bin/behat features/participant_wishlist.feature
```

### Composer script

Running the commands previously mentioned are bundled in a single composer script. This script takes care of test database creation, CA creation, server start/stop and running tests.

The only thing that needs to be done manually is running the selenium server as explained [above](#setup).

After the selenium server is running, simply run:

```bash
composer.phar run-script behat
```

## Writing tests

Behat tests are described in `.feature` files. More info on the feature file can be found 
[in the behat docs](http://docs.behat.org/en/latest/user_guide/gherkin.html#gherkin-syntax).

All new steps are described in context files. These steps can be added to the existing contexts or added to a new context.
This can be done by creating a `Context` class in `tests/Behat(/Bootstrap)` and register the context class
in the behat config file in the root directory.

## Tips

### Enable screenshots
Sometimes something is going wrong, and you want to see what's in the browser when a test is failing.

To enable screenshots, create (if it doesn't exist already) a `behat.yml` file in the project root with this content:

```yaml
imports: ["behat.yml.dist"]

default:
    extensions:
        Lakion\Behat\MinkDebugExtension:
            screenshot: true
```