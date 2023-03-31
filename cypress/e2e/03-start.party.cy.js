describe('Starting the party', () => {

    it('can start the party', () => {
        cy.task('getItem', 'adminLink').then((adminLink) => {
            cy.visit(adminLink)
            cy.get('.btn-create-party').first().click()
            cy.contains('We started your party')
        })
    })
})
