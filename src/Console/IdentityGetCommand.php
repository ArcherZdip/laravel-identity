<?php

namespace ArcherZdip\Identity\Console;

use Illuminate\Console\Command;

class IdentityGetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'identity:get
                            {--l|limit= : Get identity number}
                            {--P|province= : Set province,like `北京市`}
                            {--S|sex= : Set Sex,like `男`}
                            {--B|birth= : Set birth,like xxxx-xx-xx}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Chinese ID Number.';

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
        $limit = $this->option('limit');
        $province = $this->option('province');
        $sex = $this->option('sex');
        $birth = $this->option('birth');

        if (is_null($limit)) {
            $this->info( app('identity_faker')->province($province)
                ->sex($sex)->birth($birth)->one() );
        } else {
            $ids = app('identity_faker')->province($province)
                ->sex($sex)->birth($birth)->limit($limit)->get();

            foreach ($ids as $id) {
                $this->info($id);
            }
        }
    }
}
