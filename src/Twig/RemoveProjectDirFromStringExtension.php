<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RemoveProjectDirFromStringExtension extends AbstractExtension
{
    /**
     * @var string
     */
    private $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('removeProjectDir', [$this, 'removeProjectDir']),
        ];
    }

    public function removeProjectDir($string)
    {
        return str_replace(basename($this->projectDir), '', $string);
    }
}
