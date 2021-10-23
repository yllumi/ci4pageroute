# CodeIgniter 4 Page Route

Develop even more rapid with page based route. It is like Nextjs or Nuxtjs for CodeIgniter 4.

## Install

- Install CodeIgniter 4
- Run composer `composer require yllumi/ci4pageroute`
- Change 2 line of Config/Routes.php in CodeIgniter like this:
  ```php
  $routes->set404Override('\Yllumi\Pager\Controllers\Page::index');
  $routes->get('/', '\Yllumi\Pager\Controllers\Page::index');
  ```
- Run `composer run -d vendor/yllumi/ci4pageroute add-sample-page` to place sample pages
- Happy develop!

## Create Page

Page based route use [Latte template engine](https://latte.nette.org/) to build page structure.

By default page is placed inside folder `pages/` in root project.

```
project/
  app/
  pages/
    index.html
    meta.yml
  public/
  test/
  vendor/
  writable/
```

To make the page work, you must create at least two files, `meta.yml` and `index.html`. 
`meta.yml` is used to define page properties and `index.html` is the page template itself.

For example, create `meta.yml` inside folder `pages/` like this:

```yaml
page_title: Home
```

and create `index.html` in the same place:

```latte
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>{$page_title}</title>
</head>
<body>
  <h1>Welcome!</h1>
</body>
</html>
```

You can then call your app in browser and the root homepage will show those page files.

### Write PHP Operation

You also can write any PHP code just like you do in controller by creating file `Action.php` in the same place, with this structure:

```php
<?php

class PageAction {

  // This method handle get request
  public function run()
  {
    $data['intro'] = '<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam voluptate error laboriosam ullam odio quasi repellendus minus provident. Amet dolores repellat doloremque, nemo similique officia molestias quaerat sequi, voluptates rerum.</p>';

    return $data; 
  }


  // This method handle POST request
  public function process(){

  }

}
```

Every data inside `$data` variable you return can be called inside page template as like you do with any properties from `meta.yml`.

```latte
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>{$page_title}</title>
</head>
<body>
  <h1>Welcome!</h1>
  <p>{$intro|noescape}</p>
</body>
</html>
```

There are at least two method inside PageAction class, `run()` and `process()`. Method `run()` is used to write code to handle GET request of the page, and `process()` is used to handle POST request to that page.

### Create Subpage

You can also create any subpage by creating subfolder inside `pages/`. You can then place `meta.yml`, `index.html`, and `Action.php` inside that subfolder.

```
pages/
  about/
    index.html
    meta.yml
  index.html
  meta.yml
```

The above page structure results pages http://domain.test/ and http://domain.test/about.