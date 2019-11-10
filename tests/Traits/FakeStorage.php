<?php

namespace Inz\Repository\Test\Traits;

use Illuminate\Support\Facades\Storage;

trait FakeStorage
{
    /**
     * The instance of filesystem that points to the directory.
     *
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    private $fakeStorage;

    /**
     * Create a fake storage for testing and return the full path to it.
     *
     * @return string
     */
    private function prepareFakeStorage($name = 'app')
    {
        Storage::fake($name);
        $this->fakeStorage = Storage::disk($name);

        return storage_path('framework/testing/disks/'.$name.DIRECTORY_SEPARATOR);
    }
}
