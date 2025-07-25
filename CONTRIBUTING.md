# Contributing to Tunisia Governorate Cities for WooCommerce

Thank you for your interest in contributing to this WordPress plugin! This document provides guidelines and information for contributors.

## Getting Started

### Prerequisites

- WordPress 5.0+
- WooCommerce 5.0+
- PHP 7.4+
- Git
- Node.js (for development)

### Development Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/tunisia-governorate-cities.git
   cd tunisia-governorate-cities
   ```

2. **Install dependencies**
   ```bash
   # Install PHP dependencies
   composer install
   
   # Install Node.js dependencies
   npm install
   ```

3. **Set up WordPress development environment**
   - Install WordPress locally
   - Install and activate WooCommerce
   - Copy the plugin to `wp-content/plugins/`
   - Activate the plugin

## Development Workflow

### Code Style

- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- Use PSR-4 autoloading for PHP classes
- Follow ESLint rules for JavaScript
- Use meaningful commit messages

### Making Changes

1. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes**
   - Write clean, documented code
   - Add tests for new functionality
   - Update documentation if needed

3. **Test your changes**
   ```bash
   # Run PHP tests
   composer test
   
   # Run JavaScript linting
   npm run lint
   
   # Build assets
   npm run build
   ```

4. **Commit your changes**
   ```bash
   git add .
   git commit -m "feat: add new feature description"
   ```

5. **Push and create a pull request**
   ```bash
   git push origin feature/your-feature-name
   ```

## Code Structure

```
tunisia-governorate-cities/
├── assets/                 # Frontend assets
│   ├── css/               # Stylesheets
│   └── js/                # JavaScript files
├── languages/             # Translation files
├── src/                   # PHP source code (future)
├── tests/                 # Test files (future)
├── tunisia-governorate-cities.php  # Main plugin file
├── README.md              # Plugin documentation
├── CONTRIBUTING.md        # This file
├── composer.json          # PHP dependencies
└── package.json           # Node.js dependencies
```

## Testing

### PHP Testing
- Write unit tests using PHPUnit
- Test all public methods
- Ensure proper error handling

### JavaScript Testing
- Test AJAX functionality
- Verify form validation
- Check cross-browser compatibility

### Manual Testing
- Test on different WordPress versions
- Test with different WooCommerce versions
- Test on various browsers and devices

## Pull Request Guidelines

### Before submitting a PR:

1. **Ensure code quality**
   - Run `composer phpcs` to check PHP code style
   - Run `npm run lint` to check JavaScript code style
   - Fix any issues found

2. **Test thoroughly**
   - Test on WordPress 5.0+ and WooCommerce 5.0+
   - Test the checkout process
   - Verify governorate/city filtering works
   - Check admin order display

3. **Update documentation**
   - Update README.md if needed
   - Add inline code comments
   - Update changelog

### PR Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Tested on WordPress 5.0+
- [ ] Tested with WooCommerce 5.0+
- [ ] Tested checkout process
- [ ] Tested governorate/city filtering
- [ ] Tested admin order display

## Checklist
- [ ] Code follows WordPress coding standards
- [ ] JavaScript follows ESLint rules
- [ ] No console errors
- [ ] Responsive design maintained
- [ ] Accessibility features preserved
```

## Reporting Issues

### Bug Reports

When reporting bugs, please include:

1. **Environment details**
   - WordPress version
   - WooCommerce version
   - PHP version
   - Browser and version
   - Operating system

2. **Steps to reproduce**
   - Clear, step-by-step instructions
   - Expected vs actual behavior

3. **Additional information**
   - Error messages (if any)
   - Screenshots (if helpful)
   - Console logs (if applicable)

### Feature Requests

When requesting features:

1. **Describe the feature**
   - What should it do?
   - Why is it needed?
   - How should it work?

2. **Provide context**
   - Use case scenarios
   - Target users
   - Impact on existing functionality

## Release Process

### Versioning

We follow [Semantic Versioning](https://semver.org/):
- **MAJOR**: Breaking changes
- **MINOR**: New features (backward compatible)
- **PATCH**: Bug fixes (backward compatible)

### Release Checklist

1. **Update version numbers**
   - Main plugin file
   - package.json
   - composer.json

2. **Update changelog**
   - Add new features
   - List bug fixes
   - Note breaking changes

3. **Build assets**
   ```bash
   npm run build
   ```

4. **Create release**
   - Tag the release
   - Write release notes
   - Upload to WordPress.org (if applicable)

## Communication

- **Issues**: Use GitHub issues for bug reports and feature requests
- **Discussions**: Use GitHub discussions for general questions
- **Security**: Report security issues privately to maintainers

## Code of Conduct

- Be respectful and inclusive
- Help others learn and grow
- Focus on constructive feedback
- Follow WordPress community guidelines

## License

By contributing, you agree that your contributions will be licensed under the GPL v2 or later.

## Questions?

If you have questions about contributing, please:

1. Check the existing issues and discussions
2. Read the documentation
3. Create a new discussion or issue

Thank you for contributing to Tunisia Governorate Cities for WooCommerce! 