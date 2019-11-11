<?php

namespace Inz\Repository\Test\Traits;

use Illuminate\Support\Facades\Storage;

trait FakeStorageInitiator
{
    /**
     * The instance of filesystem that points to the directory
     *
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    private $fakeStorage;
    /**
     * The path to fake directory
     *
     * @var String
     */
    private $fakeStoragePath;

    /**
     * Create a fake storage for testing and return the full path to it.
     *
     * @return String
     */
    private function prepareFakeStorage($name = 'app')
    {
        Storage::fake($name);
        $this->fakeStorage = Storage::disk($name);
        return $this->fakeStoragePath = storage_path('framework/testing/disks/' . $name . DIRECTORY_SEPARATOR);
    }
}
