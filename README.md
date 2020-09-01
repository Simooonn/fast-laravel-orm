# laravel-orm快速操作 # 
## 安装 ##

### 1. 安装方法 ###

```
composer require hashyoo/fast-laravel-orm
```

## 使用 ##

### 1. LaravelModel ###

#### 1.1 LaravelModel引入 ####
```php
<?php

namespace Common\Model;

use HashyooFast\LaravelModel;

class Base extends LaravelModel
{
    
}
```
```php
<?php
/**
 * common model file Created by PhpStorm.
 * Date: 2020/07/07
 */

namespace Common\Model;

class Users extends Base
{
    protected $table = 'users';
    protected $guarded = [];
}

```

#### 1.2 LaravelModel使用方法 ####
```php
<?php

namespace App\Http\Controllers;
use Common\Model\Users;
class IndexController
{
    public function test(){
        //格式参考LaravelModel里的demo方法
        $arr_option = [];
        
        //获取所有数据
        User::laravelAll($arr_option);
        User::laravelOption($arr_option)->get();
        
        //指定条件数据sort字段自增5
        User::laravelWhereOption($arr_option)->increment('sort', 5);
    }
    
}
```
### 2. LaravelRepository ###

#### 2.1 LaravelRepository引入 ####
```php
<?php

namespace Common\Repository;

use HashyooFast\LaravelRepository;

class BaseRepository extends LaravelRepository
{
    
}
```
```php
<?php

namespace Common\Repository;

use Common\Model\Users;

class UsersRepository extends BaseRepository
{
    /* 该方法必须定义 */
    public function baseModel()
    {
        return new Users();
    }
}

```

#### 2.2 LaravelRepository使用方法 ####
```php
<?php

namespace App\Http\Controllers;
use Common\Repository\UsersRepository;
class IndexController
{
    public function test(){
        //格式参考LaravelModel里的demo方法
        $arr_option = [];
        
        //获取数据
        UsersRepository::find(19,$arr_option);
        UsersRepository::get_list($arr_option);
    
    }
    
}
```

### 3. 方法汇总说明 ###
```php
<?php

namespace App\Http\Controllers;
use Common\Model\Users;
use Common\Repository\UsersRepository;
class IndexController
{
    public function test(){
        /*默认软删除，即deleted_at赋值*/
        
        //格式参考LaravelModel里的demo方法
        $n_id = 108;
        $option = [];
        $data = [];
        $field = 'sort';
        $step = 5;
        
        /* Model */ 
        User::laravel_table_fields($option);//获取表字段
        User::laravelOption($option)->get();//参数设置
        User::laravelWhereOption($option)->get();//参数设置
        User::laravelAll($option);//根据条件获取全部数据
        User::laravelList($option);//根据条件获取分页数据
        User::laravelFind($n_id,$option);//根据主键查询1条数据
        User::laravelOne($option);//根据条件查询1条数据
        User::laravelCount($option);//根据条件获取统计数量
        User::laravelSum($option);//根据条件获取统计总和
        User::laravelCreate($option);//添加1条数据
        User::laravelInsert($option);//添加多条数据
        User::laravelUpdate($option);//根据条件更新数据
        User::laravelUpdateOrCreate($option);//更新数据或添加数据
        User::laravelDelete($option);//根据条件删除数据

        
        /* Repository */
        UsersRepository::find($n_id,$option);//根据主键查询1条数据
        UsersRepository::get_one($option);//根据条件查询1条数据
        UsersRepository::get_list($option);//根据条件获取分页数据
        UsersRepository::get_all($option);//根据条件获取全部数据
        UsersRepository::add_one($data);//添加一条数据
        UsersRepository::add_many($data);//添加多条数据
        UsersRepository::update($option);//根据条件修改数据
        UsersRepository::update_or_create($option);//通过条件找到相匹配的数据并更新，如果不存在即创建
        UsersRepository::get_count($option);//统计数量
        UsersRepository::get_sum($option);//统计总和
        UsersRepository::delete_one($option);//根据主键id删除一条数据-软删除
        UsersRepository::del_one_true($option);//根据主键id删除一条数据-物理删除
        UsersRepository::delete($option);//根据条件删除数据-软删除
        UsersRepository::del_true($option);//根据条件删除数据-物理删除
        UsersRepository::increase_num($n_id,$field,$step);//单条数据字段自增
        UsersRepository::decrease_num($n_id,$field,$step);//单条数据字段自减
        UsersRepository::incr_num($option,$field,$step);//根据条件多条数据字段自增
        UsersRepository::decr_num($option,$field,$step);//根据条件多条数据字段自减
    
    }
    
}
```
