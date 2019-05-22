<?php

namespace Modules\RegionBuilder\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\DB;

class RegionBuilderCommand extends Command
{
    protected static $LEVEL = [
        1 => 'provinces',
        2 => 'cities',
        3 => 'areas',
        4 => 'streets',
    ];

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
        $argument = $this->argument();
        $option = $this->option();
        try {
            //DB::beginTransaction();
            $tablename = $option['tablename'];
            $this->createTable($tablename, boolval($option['force']));
            $this->import($tablename, $argument['level']);
            //DB::commit();
        } catch (\Exception $e) {
            //DB::rollBack();
            return $this->error("错误: " . $e->getMessage());
        }
        //$provinces->save();
        //print_r($provinces->toArray());exit;
        //$o = Regions::collection($provinces);
        //print_r($o->toArray(null));
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
            ['force', 'f', InputOption::VALUE_OPTIONAL, '强制重新生成', false],
        ];
    }

    /**
     * check region table name exists 
     *
     * @return bool
     */
    protected function checkExists($tablename) 
    {
        return Schema::hasTable($tablename);
    }

    /**
     * create region table 
     *
     * @return void
     */
    protected function createTable($tablename, $force=false) 
    {
        if(empty($tablename)) {
            throw \Exception("Table name is null");
        }
        if($this->checkExists($tablename)) {
            if($force === true) {
                if ( !$this->confirm("Table `".$tablename."` is exists, Do you wish to continue?") ) {
                    throw new \Exception("Table `".$tablename."` is exists");
                }
            } else {
                throw new \Exception("Table `".$tablename."` is exists");
            }
        }
        $this->migrateTable($tablename, $force);
    }

    /**
     * migrate region table 
     *
     * @return void
     */
    private function migrateTable($tablename, $force=false) {
        if( $force === true && $this->checkExists($tablename) ) {
            // 强制重建
            Schema::drop($tablename);
        }
        Schema::create($tablename, function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('pid')->unsigned()->default(0)->comment('父类自增ID');
            $table->integer('region_grade')->unsigned()->default(0)->comment('地区层级');
            $table->string('name', 30)->comment('名称');
            $table->string('code', 12)->comment("行政区代码");
            $table->string('province_code', 2)->nullable()->comment("省份、直辖市、自治区");
            $table->string('city_code', 4)->nullable()->comment("城市");
            $table->string('area_code', 6)->nullable()->comment("区县");
            $table->string('street_code', 9)->nullable()->comment("乡镇、街道");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * import region table 
     *
     * @return void
     */
    protected function import($tablename, $level=1) {
        for($i=1; $i<=$level; $i++) {
            $json = json_decode(file_get_contents(dirname(__FILE__) . "/../Resources/regions/".self::$LEVEL[$i].".json"));
            $class = "\Modules\RegionBuilder\Entities\\" . ucfirst(self::$LEVEL[$i]);
            $items = $class::Hydrate($json)->all();
            foreach($items AS $item) {
                $model = new $class($item->getAttributes());
                $model->setTable($tablename);
                $model->region_grade = $i;
                $model->save();
                $this->info($model);
            }
        }
    }
}
