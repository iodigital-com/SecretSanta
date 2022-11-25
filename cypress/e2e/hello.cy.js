describe('Loading the home page', () => {
  it('should say "What is Secret Santa?" to unknown', () => {
    cy.visit('/')
    cy.contains('What is Secret Santa?')
  })
})
