<?php
/**
 * Handler
 *
 * PHP version 7
 *
 * @category    Board
 * @package     Xpressengine\Plugins\Board
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
namespace Xpressengine\Plugins\Board\Commands;

use App\Console\Commands\SkinMake;
use Illuminate\Support\Fluent;

/**
 * BoardSkinMake
 *
 * @category    Board
 * @package     Xpressengine\Plugins\Board
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class BoardSkinMakeWithBaseSkin extends SkinMake
{
    protected $skinStubPath = __DIR__.'/stubs/board_skin';

    protected $baseSkinDir = '/plugins/board/components/Skins/Board';

    protected $selectedSkinName;

    protected $baseSkinNames = ['Common', 'Blog'];

    protected $signature = 'make:board_skin_with_base_skin
                        {plugin : The plugin where the skin will be located}
                        {name : The name of skin to create}

                        {--id= : The identifier of skin. default "<plugin>@<name>"}
                        {--path= : The path of skin. Enter the path to under the plugin. ex) SomeDir/SkinDir}
                        {--class= : The class name of skin. default "<name>Skin"}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new board skin using base skin';

    /**
     * Execute the console command.
     *
     * @return bool|null
     * @throws \Throwable
     */
    public function handle() {
        $this->selectedSkinName = $this->selectBaseSkin();
        // get plugin info
        $plugin = $this->getPlugin();

        // get skin info
        $path = $this->getPath($this->option('path'));
        $namespace = $this->getNamespace($path);

        $className = $this->getClassName();
        $file = $this->getClassFile($path, $className);
        $id = $this->getSkinId();

        $title = $this->getTitleInput();
        $description = $this->getDescriptionInput();

        $attr = new Fluent(compact(
            'plugin',
            'path',
            'namespace',
            'className',
            'file',
            'id',
            'title',
            'description'
        ));

        // print and confirm the information of skin
        if ($this->confirmInfo($attr) === false) {
            return false;
        }

        $this->copyStubDirectory($plugin->getPath($path));

        try {

            $this->makeUsable($attr);
            $this->info('Generate the skin');

            // composer.json 파일 수정
            if (
                $this->registerComponent(
                    $plugin,
                    $id,
                    $namespace.'\\'.$className,
                    $file,
                    ['name' => $title, 'description' => $description]
                ) === false
            ) {
                throw new \Exception('Writing to composer.json file was failed.');
            }

            $this->refresh($plugin);
        } catch (\Exception $e) {
            $this->clean($path);
            throw $e;
        } catch (\Throwable $e) {
            $this->clean($path);
            throw $e;
        }

        $this->info("Skin is created successfully.");
    }

    /**
     * Copy stub directory to given path
     *
     * @param string $path given path
     * @return void
     * @throws \Exception
     */
    protected function copyStubDirectory($path)
    {
        if ($this->files->isDirectory($path) && count($this->files->files($path, true)) > 0) {
            throw new \Exception("Destination path [$path] already exists and is not an empty directory.");
        }
        if (!$this->files->copyDirectory($this->getStubPath(), $path)) {
            throw new \Exception("Unable to create directory[$path]. please check permission.");
        }

        // check asset is not exists
        if (!$this->files->isDirectory($path.'/assets') || count($this->files->files($path.'/assets', true)) < 1) {
            if (!$this->files->copyDirectory($this->skinStubPath.'/assets', $path.'/assets')) {
                throw new \Exception("Unable to create directory[$path]. please check permission.");
            }
        }

    }

    /**
     * Get a base skin for reference when creating a new skin.
     *
     * @return string
     */
    protected function selectBaseSkin()
    {
        $displayText = "[Choose base skin for new board skin]\n";

        foreach($this->baseSkinNames as $index => $baseSkinName) {
            $displayText = $displayText .  " " . ($index + 1) . ". " . $baseSkinName . "\n";
        }

        $this->info($displayText);
        $input = $this->ask('number of base skin? ');
        if ($input > count($this->baseSkinNames) || $input < 1) {
            throw new \Exception("invalid input, [$input]");
        } else {
            return $this->baseSkinNames[$input - 1];
        }

    }

    /**
     * get title
     *
     * @return string
     */
    protected function getTitleInput()
    {
        return $this->option('title') ?: studly_case($this->getComponentName()) . ' Board skin';
    }

    /**
     * get skin target
     *
     * @return string
     */
    protected function getSkinTarget()
    {
        return 'module/board@board';
    }

    /**
     * makeUsable
     *
     * @param \ArrayAccess|array $attr attributes
     * @return void
     * @throws \Exception
     */
    protected function makeUsable($attr)
    {
        $plugin = $attr['plugin'];
        $path = $plugin->getPath($attr['path']);

        $this->makeSkinClass($attr);

        $viewFileNames = ['create', 'edit', 'guestId', 'index', 'revision','show'];

        $replacePath = $plugin->getId().'/'.$attr['path'];
        foreach ($viewFileNames as $fileName) {
            $stub = sprintf('%s/views/%s.blade.php', $path, $fileName);
            if (file_exists($stub)) {
                $code = $this->files->get($stub);
                $code = "{{ XeFrontend::css('plugins/$replacePath/assets/css/skin.css')->load() }}\n" . str_replace('DummyPath', $replacePath, $code);
                $this->files->put($stub, $code);

                $rename = sprintf('%s/views/%s.blade.php', $path, $fileName);
                rename($stub, $rename);
            }
        }
    }

    /**
     * Make skin class.
     *
     * @param array $attr attributes
     * @return void
     * @throws \Exception
     */
    protected function makeSkinClass($attr)
    {
        $plugin = $attr['plugin'];
        $path = $plugin->getPath($attr['path']);

        // Xpressengine\Plugins\Board\Components\Skins\Board\
        $targetPackage = 'Xpressengine\\Plugins\\Board\\Components\\Skins\\Board\\' . $this->selectedSkinName;
        $targetClass = $this->selectedSkinName . 'Skin';
        $targetPath = 'board/components/Skins/Board/'.$this->selectedSkinName;

        $search = [$targetClass, $targetPackage, $targetPath];
        $replace = [$attr['className'], $attr['namespace'], $attr['plugin']->getId().'/'.$attr['path']];

        $this->buildFile($path.DIRECTORY_SEPARATOR.$this->selectedSkinName.'Skin.php', $search, $replace, $plugin->getPath($attr['file']));
    }

    /**
     * get stub path
     *
     * @return string
     */
    protected function getStubPath() {
        return app('path.base').$this->baseSkinDir.DIRECTORY_SEPARATOR.$this->selectedSkinName;
    }
}