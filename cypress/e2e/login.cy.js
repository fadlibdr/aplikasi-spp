describe('Login page', () => {
  it('shows Login heading', () => {
    cy.visit('/login');
    cy.contains('h1', 'Login').should('exist');
  });
});
