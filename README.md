# laravel-orm快速操作 # 
## 安装 ##

### 安装方法 ###

```
composer require hashyoo/fast-laravel-orm
```

## 使用 ##

### LaravelModel ###

#### LaravelModel引入 ####
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

#### LaravelModel使用方法 ####
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
### LaravelRepository ###

#### LaravelRepository引入 ####
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

#### LaravelRepository使用方法 ####
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
