# WSU-Press

[![Build Status](https://travis-ci.org/washingtonstateuniversity/wsupress.wsu.edu.svg?branch=master)](https://travis-ci.org/washingtonstateuniversity/wsupress.wsu.edu)

A child theme for WSU Press.

## Shortcodes

### Slideshow

`[wsu_press_slideshow]` accepts the following attributes:
* **title** - This becomes the slidshow heading. It is wrapped in an `h2` tag and is also leveraged for creating a unique `id` for the heading and the respective `aria-labelledby` attribute for the slideshow wrapper.
* **count** - The number of products to include in the slideshow. Defaults to 5. If the count is less than 9, the slideshow will automatically repeat the set of products until there are at least 9 items in the slideshow.
* **product_category_slug** - The slug of the product category to pull products from. Pulls all products by default.

### Search
`[wsu_press_product_search]` searches the WooCommerce product post type. It accepts no arguments.
