<?php namespace WebBook\ResponsiveImages;

use Backend\FormWidgets\FileUpload;
use Cms\Classes\Theme;
use Illuminate\Support\Facades\Event;
use October\Rain\Exception\ApplicationException;
use WebBook\ResponsiveImages\Classes\Focuspoint\File as FocusFile;
use WebBook\ResponsiveImages\Classes\Focuspoint\FocuspointExtension;
use WebBook\ResponsiveImages\Classes\SVG\SVGInliner;
use WebBook\ResponsiveImages\Console\ConvertCommand;
use WebBook\ResponsiveImages\Console\GenerateResizedImages;
use WebBook\ResponsiveImages\Console\Clear;
use System\Classes\PluginBase;
use System\Models\File;
use System\Traits\AssetMaker;
use URL;

/**
 * ResponsiveImages Plugin Information File
 */
class Plugin extends PluginBase
{
    use AssetMaker;

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        $this->app['Illuminate\Contracts\Http\Kernel']
            ->pushMiddleware('WebBook\ResponsiveImages\Classes\ResponsiveImagesMiddleware');

        FocuspointExtension::boot();
    }

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'webbook.responsiveimages::lang.plugin.name',
            'description' => 'webbook.responsiveimages::lang.plugin.description',
            'author'      => 'webbook.responsiveimages::lang.plugin.author',
            'homepage'    => 'https://github.com/WebBook-GmbH/oc-responsive-images-plugin',
            'icon'        => 'icon-file-image-o',
        ];
    }

    /**
     * Registers any back-end permissions.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'webbook.responsiveimages.manage_settings' => [
                'tab'   => 'webbook.responsiveimages::lang.plugin.name',
                'label' => 'webbook.responsiveimages::lang.plugin.manage_settings_permission',
            ],
        ];
    }

    /**
     * Registers any back-end settings.
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'config' => [
                'label'       => 'webbook.responsiveimages::lang.plugin.name',
                'description' => 'webbook.responsiveimages::lang.plugin.manage_settings',
                'category'    => 'system::lang.system.categories.cms',
                'icon'        => 'icon-file-image-o',
                'class'       => 'webbook\ResponsiveImages\Models\Settings',
                'order'       => 500,
                'keywords'    => 'responsive images',
                'permissions' => ['webbook.responsiveimages.manage_settings'],
            ],
        ];
    }

    public function register()
    {
        $this->registerConsoleCommand('responsiveimages:generate', GenerateResizedImages::class);
        $this->registerConsoleCommand('responsiveimages:convert', ConvertCommand::class);
        $this->registerConsoleCommand('responsiveimages:clear', Clear::class);
    }

    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'svg' => function ($path, $vars = []) {
                    $theme = Theme::getActiveTheme();
                    if (!$theme) {
                        return '';
                    }

                    $themeDir = $theme->getId();

                    // Try to fetch the file from the current theme.
                    $themePath = themes_path(implode('/', [$themeDir, $path]));
                    // If the file does not exist, check if there is a parent theme.
                    if (!file_exists($themePath) && $parentTheme = $theme->getParentTheme()) {
                        $themeDir = $parentTheme->getId();
                        $parentThemeDir = themes_path(implode('/', [$parentTheme->getId(), $path]));
                        if (file_exists($path)) {
                            $path = $parentThemeDir;
                        }
                    }

                    return (new SVGInliner($themeDir))->inline($path, $vars);
                },
            ],
        ];
    }

    /**
     * Returns the extra report widgets.
     *
     * @return  array
     */
    public function registerReportWidgets()
    {
        return [
            'WebBook\ResponsiveImages\ReportWidgets\ClearCache' => [
                'label'   => 'webbook.responsiveimages::lang.reportwidgets.clearcache.label',
                'context' => 'dashboard'
            ]
        ];
    }

}
