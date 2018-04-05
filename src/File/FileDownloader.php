<?php

namespace App\File;

use App\DateTime\DateTimeFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FileDownloader
{
    private $container;
    private $dateTimeFactory;

    public function __construct(
        ContainerInterface $container,
        DateTimeFactory $dateTimeFactory
    ) {
        $this->container = $container;
        $this->dateTimeFactory = $dateTimeFactory;
    }

    public function download(string $url, string $absolutePath)
    {
        $file = $absolutePath.$this->dateTimeFactory->now()->toTimestamp();

        $status = file_put_contents($file, fopen($url, 'r'));
        if ($status === false) {
            throw new \Exception('wrong url');
        }

        return $file;
    }

    public function downloadToCache(string $url)
    {
        $cache = $this->container->getParameter('kernel.cache_dir').'/';

        return $this->download($url, $cache);
    }

}