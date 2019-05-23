# 1. Require with Composer
```
composer require edwinlll/regionbuilder
```

# 2. Require Laravel Module

### 1. Laravel has been used

* Require Laravel 5.6 and Laravel Module V3 

```
composer require nwidart/laravel-modules
```
* Use Module

```
php artisan module:update regionbuilder
```

### 2. Don't use Laravel

* create Laravel projects:

```
composer global require laravel/installer
laravel new regions
cd regions
composer require nwidart/laravel-modules
```

* Autoloading

```
{
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "Modules\\": "Modules/"
    }
  }
}
```

# 3. Example

```
php artisan regionbuilder:generate --help

Description:
  生成最新标准地区表，默认表名为regions，表名可通过参数修改

Usage:
  regionbuilder:generate [options] [--] [<level>]

Arguments:
  level                        需要生成多少级数据 [default: 3]

Options:
  -t, --tablename[=TABLENAME]  生成的表名 [default: "regions"]
  -f, --force[=FORCE]          强制重新生成 [default: false]
  -h, --help                   Display this help message
  -q, --quiet                  Do not output any message
  -V, --version                Display this application version
      --ansi                   Force ANSI output
      --no-ansi                Disable ANSI output
  -n, --no-interaction         Do not ask any interactive question
      --env[=ENV]              The environment the command should run under
  -v|vv|vvv, --verbose         Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

* With tablename:

```
php artisan regionbuilder:generate -t myregionsname
```

* Table schema:

```
Schema::create($tablename, function(Blueprint $table)
{
    $table->increments('id');
    $table->integer('pid')->unsigned()->default(0)->comment('父类自增ID');
    $table->integer('region_grade')->unsigned()->default(0)->comment('地区层级');
    $table->string('name', 30)->comment('名称');
    $table->string('code', 12)->nullable()->comment("行政区代码");
    $table->string('province_code', 2)->nullable()->comment("省份、直辖市、自治区");
    $table->string('city_code', 4)->nullable()->comment("城市");
    $table->string('area_code', 6)->nullable()->comment("区县");
    $table->string('street_code', 9)->nullable()->comment("乡镇、街道");
    $table->timestamps();
    $table->softDeletes();
});
```

* make seeder：

Before exporting, you can modify the table structure and data according to your own requirements

```
composer require orangehill/iseed
php artisan iseed myregionsname
```
