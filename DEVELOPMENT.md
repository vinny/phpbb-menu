# Development & Quality Assurance Guide

Development tooling and testing infrastructure for Header Menu extension.

## Quality Assurance & Testing

For development, the extension comes with pre-configured static analysis and testing tools to maintain 100% code quality.

### Prerequisites

Ensure you have [Node.js](https://nodejs.org/) and [Composer](https://getcomposer.org/) installed globally on your machine.

### Setup Dependencies

To set up Node and Composer development tools, run:

```bash
npm install
composer install
```

### Running Checks

To run the complete QA suite (ESLint, Stylelint, TwigCS, PHPCS, PHPStan, and PHPUnit unit tests), execute:

```bash
npm test
```

You can also run specific checks individually:

| Script | Command | Purpose |
| :--- | :--- | :--- |
| **ESLint** | `npm run lint:js` | Lints JavaScript and HTML files (`adm/style/js`, `styles/prosilver/template`) |
| **Stylelint** | `npm run lint:css` | Lints CSS stylesheets (`styles/prosilver/theme`, `adm/style/css`) |
| **TwigCS** | `npm run lint:twig` | Lints HTML/Twig templates (`styles/prosilver/template`, `adm/style`) |
| **PHPCS** | `npm run lint:php` | Checks PHP coding standards (PSR-12 with phpBB conventions) |
| **PHPStan** | `npm run phpstan` | Performs strict PHP static analysis (Level 5) |
| **PHPUnit** | `npm run test:unit` | Executes unit, migration, and functional test suite |
