<?php

namespace App\Utils\FileSystem;

use Symfony\Component\Filesystem\Filesystem;

class FileSystemWorker
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * @param string $folder
     */
    public function createFolderIfNotExist(string $folder): void
    {
        if (!$this->filesystem->exists($folder)) {
            $this->filesystem->mkdir($folder);
        }
    }

    /**
     * @param string $item
     */
    public function remove(string $item): void
    {
        if ($this->filesystem->exists($item)) {
            $this->filesystem->remove($item);
        }
    }
}
