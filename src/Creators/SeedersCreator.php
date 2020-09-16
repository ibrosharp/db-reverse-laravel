<?php 

namespace App\Creators;

class SeedersCreator implements FileCreator {

    public function __construct(string $contents)
    {
        $this->contents = $contents;
    }

    public function createFile() : void {

    }
}