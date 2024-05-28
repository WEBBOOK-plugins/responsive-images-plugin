<?php

namespace WebBook\ResponsiveImages\Classes\Htaccess;

use RuntimeException;
use View;

class HtaccessWriter
{
    protected $contents;
    protected $path;

    public function __construct()
    {
        $htaccess = base_path('.htaccess');
        if ( ! file_exists($htaccess) || ! is_writable($htaccess)) {
            throw new RuntimeException('Cannot find .htaccess file. You have to manually configure your server for WebP support.');
        }

        $this->contents = file_get_contents($htaccess);
        $this->path     = $htaccess;
    }

    public function writeSection($section, $data = [])
    {
        if ($this->hasSection($section)) {
            $this->removeSection($section);
        }

        $view = 'webbook.responsiveimages::' . $section;

        if ( ! View::exists($view)) {
            throw new RuntimeException('Cannot find htaccess template for section ' . $section);
        }

        $template = View::make($view, $data)->render();

        $this->prependContents($template, $section);
    }

    public function removeSection($section)
    {
        $this->contents = preg_replace(
            $this->sectionRegex($section),
            '',
            $this->contents
        );
    }

    public function hasSection($section)
    {
        $section = preg_quote($section, '/');

        return (bool)preg_match_all(
            $this->sectionRegex($section),
            $this->contents
        );
    }

    public function writeContents()
    {
        return file_put_contents($this->path, $this->contents);
    }

    protected function prependContents($contents, $section)
    {
        $append   = [];
        $append[] = "## START WebBook.ResponsiveImages - ${section}";
        $append[] = '#  DO NOT REMOVE THESE LINES';
        $append[] = $contents;
        $append[] = "## END WebBook.ResponsiveImages - ${section}";

        $this->contents = implode("\n", $append) . "\n\n" .  $this->contents;
    }

    protected function sectionRegex($section)
    {
        return "/(## START WebBook\.ResponsiveImages - ${section}.*## END WebBook\.ResponsiveImages - ${section})/ims";
    }

}
