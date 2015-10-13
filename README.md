# Shop-Script Lang plugin
Shop-Script multiply languages plugin.

By default plugin uses language detection by aliases. Example 1:
```
/ - default language
/en/ - 'en' language
/fr/ - 'fr' language
```
You can use any number of settlements with one alias. Example 2:
```
/en/shop1/*
/en/shop2/*
```
each of them will be translated to 'en' language.

To change list of languages please see _config.php_

Get current language
```
{shopLangPlugin::currentLang()}
```

Get language alias
```
{shopLangPlugin::waUrl()}
```
for example returns _/en/_ if current page _/en/somesettlement/category/subcategory/product_

Super secret link to edit features translate
?plugin=lang&module=feature&action=settings
_Тупо лень было доделать_

## Usage
### One product
At the beginning of product.html
```
{shopLangPlugin::frontendProduct($product, $features, $features_selectable)}
```

### Multiply products
```
{foreach $products as $p}
  {shopLangPlugin::frontendProductList($p, $products)}
  {* your code goes here *}
{/foreach}
```

### One category
At the beginning of category.html template
```
{shopLangPlugin::frontendCategory($category)}
```

### Multiply categories
some list
```
{$categories = $wa->shop->categories(0)}
{foreach $categories as $c}
  {shopLangPlugin::frontendCategoryList($c, $categories)}
  {* your code goes here *}
{/foreach}
```

$product.categories
```
{foreach $product.categories as $c}
  {shopLangPlugin::frontendCategoryList($c, $product.categories)}
  {* your code goes here *}
{/foreach}
```

### Features
in filters
```
{shopLangPlugin::frontendFeatureList($filters)}
```

At product page features are translated by 
```
{shopLangPlugin::frontendProduct($product, $features, $features_selectable)}
```


