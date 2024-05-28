<?php

namespace WebBook\ResponsiveImages\Classes\Convert;

use Symfony\Component\Finder\SplFileInfo;

interface Converter
{
    public function convert(SplFileInfo $file);
}
