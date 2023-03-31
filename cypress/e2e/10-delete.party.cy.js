let adminLink;

before(() => {
    cy.task('getItem', 'adminLink').then((link) => {
        adminLink = link
    })
})

describe('Deleting a party', () => {
    it('can delete the party', () => {
        cy.visit(adminLink)

        cy.get('#btn_delete').click()
        cy.get('input#delete-confirmation').type('delete everything')
        cy.get('#btn_delete_confirmation').click()

        cy.contains('Your Secret Santa list was deleted')
    })
})
