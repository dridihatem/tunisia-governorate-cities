# Tunisia Governorate Cities for WooCommerce

A WordPress plugin that adds Tunisian governorates and cities dropdown to WooCommerce checkout with dynamic filtering functionality.

## Features

- **Complete Tunisian Governorates**: All 24 governorates of Tunisia
- **Dynamic City Filtering**: Cities are filtered based on selected governorate
- **WooCommerce Integration**: Seamlessly integrates with WooCommerce checkout
- **Billing & Shipping Support**: Works for both billing and shipping addresses
- **AJAX Loading**: Fast, dynamic city loading without page refresh
- **Responsive Design**: Mobile-friendly interface
- **Accessibility**: WCAG compliant with proper ARIA labels
- **Validation**: Built-in form validation
- **Admin Display**: Governorate and city information displayed in order details

## Installation

### Method 1: Manual Installation

1. Download the plugin files
2. Upload the `tunisia-governorate-cities` folder to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Ensure WooCommerce is installed and activated

### Method 2: WordPress Admin

1. Go to Plugins > Add New
2. Click "Upload Plugin"
3. Choose the plugin zip file
4. Click "Install Now"
5. Activate the plugin

## Requirements

- WordPress 5.0 or higher
- WooCommerce 5.0 or higher
- PHP 7.4 or higher

## Usage

### For Customers

1. Go to your WooCommerce checkout page
2. You'll see two new dropdown fields:
   - **Governorate**: Select your Tunisian governorate
   - **City**: Select your city (filtered based on governorate)
3. The city dropdown will automatically populate with cities from the selected governorate
4. Both fields are required for checkout

### For Administrators

The plugin automatically:
- Adds governorate and city fields to checkout
- Validates that both fields are filled
- Saves the data with orders
- Displays governorate and city information in order details

## Governorates Included

The plugin includes all 24 Tunisian governorates:

1. **Ariana** - Ariana, La Marsa, Carthage, Sidi Thabet, Raoued, Kalâat el Andalous, Ettadhamen
2. **Béja** - Béja, Testour, Nefza, Thibar, Amdoun, Goubellat
3. **Ben Arous** - Ben Arous, Hammam Lif, Hammam Chott, Bou Mhel el Bassatine, El Mourouj, Ezzahra, Radès, Fouchana, Mohamedia, Mornag, Khalidia
4. **Bizerte** - Bizerte, Mateur, Menzel Bourguiba, Ghardimaou, Sejnane, Utique, Ras Jebel, El Alya
5. **Gabès** - Gabès, Médenine, Matmata, El Hamma, Ghannouch, Oued Ellil, Nouvelle Matmata
6. **Gafsa** - Gafsa, El Ksar, Metlaoui, Redeyef, Moularès, Sidi Aich, Belkhir
7. **Jendouba** - Jendouba, Tabarka, Aïn Draham, Fernana, Beni Mtir, Ghardimaou, Bou Salem, Balta Bou Aouene
8. **Kairouan** - Kairouan, Haffouz, Alaa, Hajeb El Ayoun, Nasrallah, Menzel Mehiri, El Ouslatia, Chebika, Echebbi
9. **Kasserine** - Kasserine, Sbeitla, Feriana, Thala, Hassi El Ferid, Foussana, Jedelienne, El Ayoun, Ezzouhour, Majel Bel Abbès, Tajerouine
10. **Kébili** - Kébili, Douz, Faouar, Souk El Ahed
11. **Le Kef** - Le Kef, Tajerouine, Kalâat Senan, Kalâat Khasba, Sakiet Sidi Youssef, El Kors, Jerissa, Touiref, Dahmani, Sers, Nadhour
12. **Mahdia** - Mahdia, Rejiche, Bou Merdes, Ouled Chamekh, Chorbane, Hbira, Souassi, Kerker, Chebba, Mellouleche, Sidi Alouane, El Jem
13. **Manouba** - Manouba, Den Den, Douar Hicher, Oued Ellil, Mornaguia, Borj El Amri, Djedeida, Tebourba, El Battan, Jedaida, Kalâat El Andalous
14. **Médenine** - Médenine, Ben Guerdane, Zarzis, Djerba Ajim, Djerba Midoun, Djerba Houmt Souk, Sidi Makhlouf, Bin Qirdane, Ajim, El Hamma, Ghoumrassen, Smar
15. **Monastir** - Monastir, Khniss, Jemmal, Ksar Hellal, Kantaoui, Sahline, Ouerdenine, Bembla, Téboulba, Lamta, Bekalta, Touza, Sayada, Barkoukech
16. **Nabeul** - Nabeul, Hammamet, Kelibia, Dar Chaabane, Takelsa, Soliman, Korbous, Menzel Temime, Bni Khiar, El Haouaria, Tazarka, Mida, Ouled Ellah, Azmour, Hammam Ghezaz, El Mida
17. **Sfax** - Sfax, Sakiet Ezzit, Chihia, Skhira, Jebeniana, Agareb, El Hencha, Gremda, Thyna, Kerkenah, El Amra, Bir Ali Ben Khalifa, Gabès, Métouia, Oued Ellil, Sidi El Hani
18. **Sidi Bouzid** - Sidi Bouzid, Regueb, Sidi Ali Ben Aoun, Mezouna, Oued Ellil, Bir El Haffey, Celta, Jilma, Menzel Bouzaiane, Souk Jedid, Sidi El Hani, Ouled Haffouz
19. **Siliana** - Siliana, Bou Arada, Gaafour, El Krib, Sidi Bou Rouis, Maktar, Rouhia, Kesra, El Aroussa, Bargou, Le Sers, Makthar
20. **Sousse** - Sousse, Kantaoui, Hammam Sousse, Akouda, Kalâa Kebira, Kalâa Sghira, Msaken, Sidi Bou Ali, Hergla, Ennadhour, Boulel, Sidi El Heni, Messaadine
21. **Tataouine** - Tataouine, Ghomrassen, Remada, Bir Lahmar, Dehiba, Smar, Toujane, El Borma
22. **Tozeur** - Tozeur, Nefta, Degache, Hazoua, El Hamma du Jérid, Chebika, Midès, Tamerza
23. **Tunis** - Tunis, Le Bardo, La Goulette, Carthage, Sidi Bou Said, Marsa, Gammarth, Kram, Mohamedia, El Omrane, El Omrane Supérieur, Bab Souika, Bab El Bhar, Medina, Sejoumi, El Kabaria, Sidi Hassine, Ezzouhour, El Menzah (1-20)
24. **Zaghouan** - Zaghouan, El Fahs, Zriba, Bir Mcherga, Djebel Oust, El Haouaria, Nadhour, Saouaf

## Customization

### Styling

The plugin includes CSS that can be customized. The main styles are in `assets/css/tunisia-governorate-cities.css`.

### Adding Custom Governorates/Cities

To add custom governorates or cities, modify the `get_governorates()` and `get_cities()` methods in the main plugin file.

### Hooks and Filters

The plugin provides several hooks for customization:

```php
// Filter governorates
add_filter('tgc_governorates', 'my_custom_governorates');

// Filter cities for a specific governorate
add_filter('tgc_cities_ariana', 'my_custom_ariana_cities');

// Modify field settings
add_filter('tgc_checkout_fields', 'my_custom_field_settings');
```

## Troubleshooting

### Common Issues

1. **Fields not appearing**: Ensure WooCommerce is activated and you're on the checkout page
2. **Cities not loading**: Check browser console for JavaScript errors
3. **Validation errors**: Ensure both governorate and city are selected

### Debug Mode

Enable WordPress debug mode to see detailed error messages:

```php
// In wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Support

For support and feature requests, please create an issue on the plugin's GitHub repository.

## Changelog

### Version 1.0.0
- Initial release
- Complete Tunisian governorates and cities
- Dynamic filtering functionality
- WooCommerce integration
- Responsive design
- Accessibility features

## License

This plugin is licensed under the GPL v2 or later.

## Credits

- Developed for Tunisian e-commerce websites
- Compatible with WooCommerce
- Built with accessibility in mind

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Security

To report security issues, please contact the plugin author directly rather than using the public issue tracker. 