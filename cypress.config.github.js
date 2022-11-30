const { defineConfig } = require('cypress')

module.exports = defineConfig({
  e2e: {
    baseUrl: 'https://127.0.0.1:8000',
    setupNodeEvents(on, config) {
      const items = {}

      on('task', {
        setItem ({ name, value }) {
          if (typeof value === 'undefined') {
            // since we cannot return undefined from the cy.task
            // let's not allow storing undefined
            throw new Error(`Cannot store undefined value for item "${name}"`)
          }

          items[name] = value

          return null
        },

        getItem (name) {
          if (name in items) {
            return items[name]
          }

          const msg = `Missing item "${name}"`

          console.error(msg)
          throw new Error(msg)
        },
      })
    }
  }
})