<?php namespace Yorki\Workshop\Console\Commands;

use \Illuminate\Console\Command;
use \Illuminate\Filesystem\Filesystem;

class WorkshopAdmin extends Command
{
    /**
     * @var string
     */
    protected $signature = 'workshop:admin';

    /**
     * @var string
     */
    protected $description = 'Makes admin panel for app';

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @param \Illuminate\Filesystem\Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    public function handle()
    {
        if (!$this->checkDependencies()) {
            return;
        }

        $paths = [
            base_path('resources/views/layouts'),
            base_path('resources/views/admin/users'),
            base_path('resources/views/widgets'),
            base_path('app/Http/Controllers/Admin'),
            base_path('app/Widgets'),
        ];

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                $this->files->makeDirectory($path, 0755, true);
            }
        }

        $this->files->copy(__DIR__ . '/Stubs/views/layouts/admin.blade.php', base_path('resources/views/layouts/admin.blade.php'));
        $this->files->copy(__DIR__ . '/Stubs/views/admin/dashboard.blade.php', base_path('resources/views/admin/dashboard.blade.php'));
        $this->files->copy(__DIR__ . '/Stubs/views/admin/users/index.blade.php', base_path('resources/views/admin/users/index.blade.php'));
        $this->files->copy(__DIR__ . '/Stubs/views/admin/users/single.blade.php', base_path('resources/views/admin/users/single.blade.php'));
        $this->files->copy(__DIR__ . '/Stubs/Controllers/AdminController.stub', base_path('app/Http/Controllers/Admin/AdminController.php'));
        $this->files->copy(__DIR__ . '/Stubs/Controllers/IndexController.stub', base_path('app/Http/Controllers/Admin/IndexController.php'));
        $this->files->copy(__DIR__ . '/Stubs/Controllers/UserController.stub', base_path('app/Http/Controllers/Admin/UserController.php'));
        $this->files->copy(__DIR__ . '/Stubs/Widgets/AdminSidebar.stub', base_path('app/Widgets/AdminSidebar.php'));
        $this->files->copy(__DIR__ . '/Stubs/views/widgets/admin_sidebar.blade.php', base_path('resources/views/widgets/admin_sidebar.blade.php'));
        $this->files->copy(__DIR__ . '/Stubs/Widgets/AdminHeader.stub', base_path('app/Widgets/AdminHeader.php'));
        $this->files->copy(__DIR__ . '/Stubs/views/widgets/admin_header.blade.php', base_path('resources/views/widgets/admin_header.blade.php'));

        $web = $this->files->get(base_path('routes/web.php'));

        if (strpos($web, '[@Admin-Routes@]') === false) {
            $web .= PHP_EOL . $this->files->get(__DIR__ . '/Stubs/web.stub');
            $this->files->put(base_path('routes/web.php'), $web);
        }

        $this->addLinkToSidebar('/admin/users', 'Users');

        $adminLTEPath = storage_path('admin-lte-2.4.3.zip');
        $this->downloadAdminLTE($adminLTEPath);
        $this->installAdminLTE($adminLTEPath);
    }

    /**
     * @return bool
     */
    protected function checkDependencies()
    {
        if (!class_exists('\Arrilot\Widgets\ServiceProvider')) {
            $this->output->error('Laravel widgets are not installed!');
            $this->output->success('Type "composer require arrilot/laravel-widgets" to install');

            return false;
        }

        return true;
    }

    /**
     * @param string $path
     */
    protected function downloadAdminLTE($path)
    {
        $this->output->title('Downloading Admin LTE');

        $client = new \GuzzleHttp\Client([
            'http_errors' => false,
            'timeout' => 5,
        ]);

        $resource = fopen($path, 'w');
        $stream = \GuzzleHttp\Psr7\stream_for($resource);
        $res = $client->request('GET', 'https://github.com/almasaeed2010/AdminLTE/archive/v2.4.3.zip', [
            'stream' => true,
            'save_to' => $stream,
        ]);

        $body = $res->getBody();
        $progress = $this->output->createProgressBar($res->getHeader('Content-Length')[0]);

        while (!$body->eof()) {
            $buffer = $body->read(1024);
            fwrite($resource, $buffer);
            $progress->advance(strlen($buffer));
        }

        fclose($resource);

        $progress->finish();
    }

    /**
     * @param string $zipPath
     */
    protected function installAdminLTE($zipPath)
    {
        $this->output->title('Extracting Admin LTE');

        $zip = new \ZipArchive();

        if ($zip->open($zipPath)) {
            //$zip->extractTo(storage_path('admin-lte'));
            $zip->close();
        }

        $this->output->title('Installing Admin LTE');

        $adminLTERoot = storage_path('admin-lte/AdminLTE-2.4.3');
        $publicLTERoot = public_path('vendor/admin-lte');

        $paths = [
            $publicLTERoot . '/plugins',
            public_path('vendor/bootstrap'),
            public_path('vendor/font-awesome/css'),
            public_path('vendor/font-awesome/fonts'),
            public_path('vendor/ionicons/css'),
            public_path('vendor/ionicons/fonts'),
            public_path('vendor/jquery'),
            public_path('vendor/fastclick'),
            public_path('vendor/ckeditor'),
            public_path('vendor/flot'),
        ];

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                $this->files->makeDirectory($path, 0755, true);
            }
        }

        $this->files->copyDirectory($adminLTERoot . '/dist', $publicLTERoot);
        $this->files->copyDirectory($adminLTERoot . '/plugins', $publicLTERoot . '/plugins');
        $this->files->copyDirectory($adminLTERoot . '/bower_components/bootstrap/dist', public_path('vendor/bootstrap'));
        $this->files->copyDirectory($adminLTERoot . '/bower_components/font-awesome/css', public_path('vendor/font-awesome/css'));
        $this->files->copyDirectory($adminLTERoot . '/bower_components/font-awesome/fonts', public_path('vendor/font-awesome/fonts'));
        $this->files->copyDirectory($adminLTERoot . '/bower_components/Ionicons/css', public_path('vendor/ionicons/css'));
        $this->files->copyDirectory($adminLTERoot . '/bower_components/Ionicons/fonts', public_path('vendor/ionicons/fonts'));
        $this->files->copyDirectory($adminLTERoot . '/bower_components/jquery/dist', public_path('vendor/jquery'));
        $this->files->copy($adminLTERoot . '/bower_components/jquery-slimscroll/jquery.slimscroll.js', public_path('vendor/jquery/jquery.slimscroll.js'));
        $this->files->copy($adminLTERoot . '/bower_components/jquery-slimscroll/jquery.slimscroll.min.js', public_path('vendor/jquery/jquery.slimscroll.min.js'));
        $this->files->copyDirectory($adminLTERoot . '/bower_components/fastclick/lib', public_path('vendor/fastclick'));
        $this->files->copyDirectory($adminLTERoot . '/bower_components/ckeditor', public_path('vendor/ckeditor'));
        $this->files->copyDirectory($adminLTERoot . '/bower_components/Flot', public_path('vendor/flot'));

        $this->output->success('Admin LTE installed');
    }

    /**
     * @param string $link
     * @param string $caption
     * @throws \Exception
     */
    protected function addLinkToSidebar($link, $caption)
    {
        $sidebar = $this->files->get(base_path('resources/views/widgets/admin_sidebar.blade.php'));
        $links = mb_strpos($sidebar, '<!--[@MENU-LINKS@]-->');

        if ($links === false) {
            throw new \Exception('Placeholder for links not found!');
        }

        $tmp = mb_substr($sidebar, 0, $links);
        $tmp .= '<li><a href="' . $link . '"><i class="fa fa-dashboard"></i> <span>' . $caption . '</span></a></li>' . PHP_EOL;
        $tmp .= mb_substr($sidebar, $links);

        $this->files->put(base_path('resources/views/widgets/admin_sidebar.blade.php'), $tmp);
    }
}