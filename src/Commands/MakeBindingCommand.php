<?php

namespace Inz\Commands;

use Inz\Base\Abstractions\Command;

class MakeBindingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bind:repository {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add repository binding of the given model to service provider.';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->isValidArgument('model')) {
            $this->missingArgumentError('model');
            return;
        }

        $this->prepareForBindRepository();

        if (!$this->providerAssistor->providerExist()) {
            $this->call('make:provider', ['name' => $this->providerName]);
            $this->providerAssistor->replaceContent();
        } elseif ($this->providerAssistor->isRepositoryBound($this->contractCreator->getClassName())) {
            $this->warn("Model's repository already bound in the service provider");
            return;
        }

        if ($this->bind()) {
            $this->info('Repository bound successfully');
            return;
        }

        $this->warn('Error while binding repository!');
    }

    /**
     * Perform the binding process
     *
     * @return bool
     **/
    private function bind()
    {
        $contract = $this->contractCreator->getClassFullNamespace();
        $implementation = $this->repositoryCreator->getClassFullNamespace();
        return $this->providerAssistor->addRepositoryEntry($contract, $implementation);
    }
}
