<?php namespace Yllumi\Pager\Controllers;

use App\Controllers\BaseController;
use Symfony\Component\Yaml\Yaml;

class Page extends BaseController
{
    public function __construct()
    {
        $this->pagePath = ROOTPATH . 'pages/';
    }

    public function index()
    {
        $Uri = service('uri');
        $uri = $Uri->getSegments();

        // Process static page in theme/pages/ folder
        $page = $this->page_detail($uri);

        // If not logged in, send to login form
        if(($page['require_login'] ?? null) && !$this->ci_auth->isLoggedIn())
            redirect('auth/login?red='.$this->uri->uri_string());

        // Finally, Render page
        $output = $this->render($this->pagePath . $page['content_files']['index'], $page, true);

        echo $output;
    }

    private function page_detail($segments, $customdata = [], $return_as_string = false)
    {
        // pecah segment url
        $strseg = implode('/', $segments);

        // Ambil page, Kalo page 404 pun ga ada juga, show 404 bawaan ci
        if(! $page = $this->get_page($strseg))
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();


        // Run page action class
        $pagedata = [];
        if(file_exists($page['path'].'/Action.php')){
            include_once($page['path'].'/Action.php');
            $Action = new \PageAction;

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $Action->process();
            } else {
                $pagedata = $Action->run() ?? [];
            }
        }

        // merge page data and other custom data
        $page = array_merge($page, $pagedata);

        return $page;
    }

    private function get_page($url = null, $parse = true)
    {
        // get page fields
        if(! $pagedata = $this->page_exist($url)){
            http_response_code(404);
            if(! $pagedata = $this->page_exist('404'))
                return false;
        }
        
        // get another md or html file as custom fields
        $files = scandir($pagedata['path']);

        foreach ($files as $file) {
            if(is_dir($pagedata['path'].'/'.$file)) continue;

            $filepath = pathinfo($pagedata['path'].'/'.$file);

            // Get file with extension .html
            if($filepath['basename'] == 'index.html')
            {
                // Prepare page files
                $pagedata['content_files_num'][] = $pagedata['uri'].'/'.$file;
                $pagedata['content_files'][$filepath['filename']] = $pagedata['uri'].'/'.$file;
            }
        }

        $pagedata['url'] = $pagedata['uri'];
        $file_segment = explode('/', $pagedata['uri']);
        if(! empty($pagedata['uri'])){
            $pagedata['slug'] = array_pop($file_segment);
            if(! empty($pagedata['uri']))
                $pagedata['parent'] = implode('/', $file_segment);
        }

        return $pagedata;
    }

    /**
     * search if page is exist
     *
     * @access  private
     * @param   string  category, null for get all
     * @param   int     page number
     * @return  array
     */
    private function page_exist($url = null, $remain_uri = '')
    {
        if(file_exists(realpath($this->pagePath.$url.'/meta.yml'))){
            $pagePath = realpath($this->pagePath.$url);
            $metaFile = $pagePath.'/meta.yml';
        }
        else {
            if(!empty($url)){
                $url = explode('/', $url);
                $remain = array_pop($url);
                $url = implode('/', $url);
                return $this->page_exist($url, $remain);
            }
            return false;
        }

        $pagedata = Yaml::parseFile($metaFile);
        $pagedata['uri'] = $url;
        $pagedata['path'] = $pagePath;

        // Accept next non-page uri as param or not 
        if(!empty($remain_uri))
            if(!($pagedata['accept_param_uri'] ?? false))
                return false;

        return $pagedata;
    }

    private function render($path, $data, $return = false)
    {
        $latte = new \Latte\Engine;
        $latte->setTempDirectory('../writable/cache/latte');

        // render to output
        if($return)
            return $latte->renderToString($path, $data);
        
        $latte->render($path, $data);
    }

}
