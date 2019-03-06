<?php

namespace ArcherZdip\Identity\Console;

use ArcherZdip\Identity\VerityChineseIDNumber;
use Illuminate\Console\Command;

class IdentityVerityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'identity:verity
                            {idnumber : Chinese ID number string}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verity Chinese ID number';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        $idnumber = $this->argument('idnumber');

        $isVerity = VerityChineseIDNumber::isValid($idnumber);

        if($isVerity) {
            $this->info("The {$idnumber} is correct!");
        } else {
            $this->error("The {$idnumber} is incorrectness!");
        }
    }
}
