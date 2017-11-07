<?php

namespace App\Product\Importer;

interface ImporterInterface
{
    /**
     * @param string $pathToFile
     *
     * @throws Exception\ExceptionInterface
     */
    public function import(string $pathToFile) : void;
}
