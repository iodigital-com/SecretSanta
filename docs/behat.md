# Behat

## Running tests

This test suite uses google chrome to run the behat features. To run the tests locally you will need to download the 
[google chrome webdriver here](https://sites.google.com/a/chromium.org/chromedriver/downloads). Download a version which supports your local chrome version.

Start selenium with the google chrome webdriver

```bash
bin/selenium-server-standalone -Dwebdriver.chrome.driver=/path/to/chromedriver
```

Start the behat suite

```bash
bin/behat
```

or run a specific feature

```bash
bin/behat src/Intracto/Behat/Features/participant_wishlist.feature
```

## Writing tests

Behat tests are described in `.feature` files. More info on the feature file can be found 
[in the behat docs](http://docs.behat.org/en/latest/user_guide/gherkin.html#gherkin-syntax).

All new steps are described in context files. These steps can be added to the existing contexts or added to a new context.
This can be done by creating a `Context` class in `Intracto/Behat/Features/Context(/Bootstrap)` and register the context class
in the behat config file in the root directory.
