# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Initial plugin structure
- Development documentation
- Contributing guidelines

## [1.0.1] - 2024-01-XX

### Added
- HPOS (High-Performance Order Storage) compatibility check
- Warning message when HPOS is enabled
- Automatic plugin deactivation when HPOS is detected
- Updated documentation with HPOS compatibility notes

### Fixed
- Plugin now properly handles WooCommerce HPOS incompatibility
- Prevents plugin activation when HPOS is enabled to avoid conflicts

## [1.0.2] - 2024-01-XX

### Changed
- Removed loading animation from city dropdown to improve performance
- Simplified JavaScript code by removing loading states and animations
- Updated CSS to remove loading spinner styles and animations

### Added
- Enhanced WordPress internationalization (i18n) support
- Added all missing translatable strings to POT file
- Improved translation coverage for validation messages and admin display

## [1.0.3] - 2024-01-XX

### Added
- Integrated Select2 library for enhanced dropdown functionality
- Searchable dropdowns for governorates and cities
- Improved user experience with better dropdown interaction
- Custom Select2 styling to match WooCommerce theme
- Responsive design support for Select2 dropdowns
- Dark theme and high contrast support for Select2
- Accessibility improvements with proper ARIA labels

### Changed
- Updated JavaScript to initialize and manage Select2 dropdowns
- Enhanced CSS with comprehensive Select2 styling
- Improved dropdown performance and user interaction
- Better mobile experience with touch-friendly dropdowns

## [1.0.0] - 2024-01-01

### Added
- Complete Tunisian governorates and cities data
- Dynamic filtering functionality with AJAX
- WooCommerce checkout integration
- Billing and shipping address support
- Form validation and error handling
- Responsive design for mobile devices
- Accessibility features (WCAG compliant)
- Loading states and visual feedback
- Admin order display for governorate/city data
- Internationalization support
- Clean uninstall process
- Comprehensive documentation

### Features
- **24 Tunisian Governorates**: Complete list with proper naming
- **Dynamic City Filtering**: Real-time city loading based on governorate selection
- **WooCommerce Integration**: Seamless checkout form integration
- **Mobile Responsive**: Touch-friendly interface for mobile devices
- **Accessibility**: Screen reader support, keyboard navigation, high contrast mode
- **Security**: Nonce verification, data sanitization, XSS prevention
- **Performance**: Lightweight, caching compatible, optimized AJAX

### Technical Implementation
- **PHP**: Object-oriented design with WordPress hooks
- **JavaScript**: jQuery integration with error handling
- **CSS**: Modern styling with accessibility features
- **Database**: Order meta storage for governorate/city data

### Browser Support
- Chrome, Firefox, Safari, Edge (latest versions)
- iOS Safari, Chrome Mobile, Samsung Internet
- Screen readers and assistive technologies

### Security
- Nonce verification for all AJAX requests
- Input validation and sanitization
- SQL injection protection
- XSS prevention through output escaping

## [0.1.0] - 2023-12-01

### Added
- Initial development version
- Basic plugin structure
- Governorate and city data arrays
- Simple checkout field integration

---

## Version History

### Version 1.0.0 (Current)
- **Release Date**: January 1, 2024
- **Status**: Stable
- **WordPress**: 5.0+
- **WooCommerce**: 5.0+
- **PHP**: 7.4+

### Version 0.1.0 (Development)
- **Release Date**: December 1, 2023
- **Status**: Development
- **WordPress**: 5.0+
- **WooCommerce**: 5.0+
- **PHP**: 7.4+

---

## Migration Guide

### From Version 0.1.0 to 1.0.0
- No breaking changes
- Enhanced functionality and improved user experience
- Better error handling and validation
- Improved accessibility features

---

## Support

For support and questions:
- Create an issue on GitHub
- Check the documentation
- Review the troubleshooting guide

---

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for details on how to contribute to this project.

---

## License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details. 