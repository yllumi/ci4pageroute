# CodeIgniter 4 Page Route

Develop even more rapid with page based route. It is like Nextjs or Nuxtjs for CodeIgniter 4.

## Install

- Install CodeIgniter 4
- Run composer `composer require yllumi/ci4pageroute`
- Change 2 line of Config/Routes.php in CodeIgniter like this:
  ```
  $routes->set404Override('\Yllumi\Pager\Controllers\Page::index');
  $routes->get('/', '\Yllumi\Pager\Controllers\Page::index');
  ```
- Run `composer run -d vendor/yllumi/ci4pageroute add-sample-page` to place sample pages
- Happy develop!
