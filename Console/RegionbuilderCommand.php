<?php

namespace Modules\RegionBuilder\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use \Modules\RegionBuilder\Transformers\Regions;
use \Modules\RegionBuilder\Entities\Provinces;

class RegionBuilderCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'regionbuilder:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成最新标准地区表，默认表名为regions，表名可通过参数修改';

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
        print_r($this->argument());
        print_r($this->option());

        $json = json_decode(file_get_contents(dirname(__FILE__) . "/../Resources/regions/provinces.json"));
        $provinces = Provinces::Hydrate($json);
        //print_r($provinces->toArray());exit;
        $aa = "collection";
        $o = Regions::collection($provinces);
        print_r($o->toArray([]));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['level', InputArgument::OPTIONAL, '需要生成多少级数据', 3],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['tablename', 't', InputOption::VALUE_OPTIONAL, '生成的表名', 'regions'],
        ];
    }
}
