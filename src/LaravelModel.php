<?php

namespace HashyooFast;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class LaravelModel extends Model
{

    use SoftDeletes;


    private function laravel_model()
    {
        $child_model_class = get_called_class();
        return new $child_model_class;
    }

    /**
     * $option参数示例
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    private function demo()
    {
        $option = [
          'with'       => ['aa', 'bb'],
          'where'      => [
            'is_menu' => 1,//类型1
            ['status', '!=', 2],//类型2
            ['name', 'like', '习俗'],//类型3
          ],
          'whereIn'    => [
            'role_id'   => [6, 8, 22],
            'user_name' => ['张三', '李四'],
          ],
          'whereNotIn' => [
            'role_id'   => [6, 8, 22],
            'user_name' => ['张三', '李四'],
          ],
          'orWhere'    => [
            'is_menu' => 1,//类型1
            ['status', '!=', 2],//类型2
            ['name', 'like', '习俗'],//类型3
          ],
          'field'      => ['id', 'name', 'title'],
          'withCount'  => ['aa', 'bb'],
          //order排序，谁在前谁优先
          'orderBy'    => [
            'sort' => 'asc',
            'id'   => 'asc',
          ],
          'limit'      => 15,
          'sum'        => 'score',
          'whereHas'   => [
            'user.goods' => [
              'where'   => [
                'is_menu' => 1,//类型1
                ['status', '!=', 2],//类型2
                ['name', 'like', '习俗'],//类型3
              ],
              'whereIn' => [
                'role_id'   => [6, 8, 22],
                'user_name' => ['张三', '李四'],
              ],
              //... ...
            ],
          ],

        ];
    }

    /**
     * 获取表字段
     *
     * @return array
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function laravel_table_fields()
    {
        $prefix       = DB::getConfig('prefix');
        $table        = self::laravel_model()->table;
        $s_full_table = $prefix . $table;
        $columns      = DB::getDoctrineSchemaManager()
                          ->listTableColumns($s_full_table);
        $arr_data     = [];
        foreach ($columns as $column) {
            $arr_data[] = $column->getName();
        }
        return $arr_data;
    }

    /* 基础 option */
    private function laravel_option($option = [])
    {
        $option            = array_change_key_case($option, CASE_LOWER);
        $option['field']   = isset($option['field']) ? $option['field'] : ['*'];//默认查询字段
        $option['orderby'] = isset($option['orderby']) ? $option['orderby'] : [];//多个排序方式
        $option['limit']   = isset($option['limit']) ? $option['limit'] : 10;//默认每页查询条数
        return $option;
    }

    private function laravel_where_option()
    {
        return yoo_array_value_lower(['where', 'orWhere', 'whereIn', 'whereNotIn', 'whereHas']);
    }

    /* with */
    private function laravel_with($query, $arr_data)
    {
        $query = $query->with($arr_data);
        return $query;
    }

    /* where */
    private function laravel_where($query, $arr_data)
    {
        foreach ($arr_data as $key => $value) {
            if (is_array($value)) {
                if ($value[1] == 'like') {
                    $query = $query->where($key, 'like', '%' . $value . '%');
                }
                else {
                    $query = $query->where($value[0], $value[1], $value[2]);
                }
            }
            else {
                $query = $query->where($key, $value);
            }
        }
        return $query;
    }

    /* orWhere */
    private function laravel_orwhere($query, $arr_data)
    {
        foreach ($arr_data as $key => $value) {
            if (is_array($value)) {
                if ($value[1] == 'like') {
                    $query = $query->orWhere($key, 'like', '%' . $value . '%');
                }
                else {
                    $query = $query->orWhere($value[0], $value[1], $value[2]);
                }
            }
            else {
                $query = $query->orWhere($key, $value);
            }
        }
        return $query;
    }

    /* whereIn */
    private function laravel_wherein($query, $arr_data)
    {
        foreach ($arr_data as $key => $value) {
            if (!empty($value)) {
                $query = $query->whereIn($key, $value);
            }
        }
        return $query;
    }

    /* whereNotIn */
    private function laravel_wherenotin($query, $arr_data)
    {
        foreach ($arr_data as $key => $value) {
            if (!empty($value)) {
                $query = $query->whereNotIn($key, $value);
            }
        }
        return $query;
    }

    /* select */
    private function laravel_select($query, $arr_data)
    {
        $query = $query->select($arr_data);
        return $query;
    }

    /* withCount */
    private function laravel_withcount($query, $arr_data)
    {
        $query = $query->withCount($arr_data);
        return $query;
    }

    /* orderBy */
    private function laravel_order($query, $arr_data)
    {
        foreach ($arr_data as $key => $value) {
            $query = $query->orderBy($key, $value);
        }
        return $query;
    }

    /* whereHas */
    private function laravel_wherehas($query, $arr_data)
    {
        foreach ($arr_data as $key => $value) {
            $query = $query->whereHas($key,
              function ($qqqqq)
              use ($value) {
                  $value = array_change_key_case($value, CASE_LOWER);

                  foreach ($value as $kk => $vv) {
                      switch ($kk) {
                          case 'where':
                              $qqqqq = self::laravel_where($qqqqq, $vv);
                              break;
                          case 'orwhere':
                              $qqqqq = self::laravel_orwhere($qqqqq, $vv);
                              break;
                          case 'wherein':
                              $qqqqq = self::laravel_wherein($qqqqq, $vv);
                              break;
                          case 'wherenotin':
                              // 注：当关联关系是1对多时，whereNotIn效果有限，只有关联里有一个数据不符合whereNotIn条件，该数据仍然会显示
                              $qqqqq = self::laravel_wherenotin($qqqqq, $vv);
                              break;

                          default:
                      }
                  }
              });
        }
        return $query;
    }

    /**
     * 条件查询
     *
     * @param       $query
     * @param array $option
     * @param array $arr_remain
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    private function laravel_query($query, &$option = [], $arr_remain = [])
    {
        $option = self::laravel_option($option);
        if (!empty($arr_remain)) {
            $arr_remain = yoo_array_value_lower($arr_remain);
            $option     = yoo_array_remain($option, $arr_remain);
        }

        $query = !empty($option['with']) ? self::laravel_with($query, $option['with']) : $query;
        $query = !empty($option['where']) ? self::laravel_where($query, $option['where']) : $query;
        $query = !empty($option['orwhere']) ? self::laravel_orwhere($query, $option['orwhere']) : $query;
        $query = !empty($option['wherein']) ? self::laravel_wherein($query, $option['wherein']) : $query;
        $query = !empty($option['wherenotin']) ? self::laravel_wherenotin($query, $option['wherenotin']) : $query;
        $query = !empty($option['wherehas']) ? self::laravel_wherehas($query, $option['wherehas']) : $query;
        $query = !empty($option['field']) ? self::laravel_select($query, $option['field']) : $query;
        $query = !empty($option['withcount']) ? self::laravel_withcount($query, $option['withcount']) : $query;
        $query = !empty($option['orderby']) ? self::laravel_order($query, $option['orderby']) : $query;
        return $query;

    }




    /************ scope ************/

    /**
     * 参数设置
     *
     * [注] 设置['with', 'where', 'orwhere', 'wherein', 'wherenotin', 'wherehas', 'field', 'withcount', 'orderby']
     *
     *
     * @param       $query
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeLaravelOption($query, $option = [])
    {
        $result = self::laravel_query($query, $option);
        return $result;
    }

    /**
     * 参数设置
     *
     * [注] 只设置['where', 'orWhere', 'whereIn', 'whereNotIn', 'whereHas']
     *
     * @param       $query
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeLaravelWhereOption($query, $option = [])
    {
        $arr_remain = self::laravel_where_option();
        $result     = self::laravel_query($query, $option, $arr_remain);
        return $result;
    }

    /**
     * 所有数据
     *
     * @param       $query
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeLaravelAll($query, $option = [])
    {
        return self::laravel_query($query, $option)
                   ->get();
    }

    /**
     * 获取列表数据-含分页
     * @param       $query
     * @param array $option
     *
     * @return mixed
     */
    public function scopeLaravelList($query, $option = [])
    {
        return self::laravel_query($query, $option)
                   ->paginate($option['limit']);
    }

    /**
     * 查询一条数据(通过主键id查询)
     *
     * @param       $query
     * @param int   $id
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeLaravelFind($query, $id = 0, $option = [])
    {
        //查询不存在的数据时，不返回null 最好使用 laravelOption()->find()
        return self::laravel_query($query, $option, ['with', 'field', 'withCount'])
                   ->find($id);
    }

    /**
     * 查询一条数据(通过where条件查询)
     *
     * @param       $query
     * @param array $option
     *
     * @return array
     */
    public function scopeLaravelOne($query, $option = [])
    {
        return self::laravel_query($query, $option)
                   ->first();
    }

    /**
     * 获取统计数量
     *
     * @param       $query
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeLaravelCount($query, $option = [])
    {
        return self::laravel_query($query, $option)
                   ->count();
    }

    /**
     * 获取统计总和
     *
     * @param       $query
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeLaravelSum($query, $option = [])
    {
        return self::laravel_query($query, $option)
                   ->sum($option['sum']);

    }

    /**
     * 添加一条数据
     *
     * @param       $query
     * @param array $data
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeLaravelCreate($query, $data = [])
    {
        $data = yoo_array_remain_trim($data, self::laravel_table_fields());
        return $query->create($data);
    }

    /**
     * 添加多条数据
     *
     * @param       $query
     * @param array $data
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeLaravelInsert($query, $data = [])
    {
        //TODO 处理字段，表中不存在的字段，自动去除
        //        $data = yoo_array_remain_trim($data, self::laravel_table_fields());
        return $query->insert($data);
    }

    /**
     * 更新数据
     *
     * @param       $query
     * @param array $data
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeLaravelUpdate($query, $data = [], $option = [])
    {
        $data = yoo_array_remain_trim($data, self::laravel_table_fields());
        return self::laravel_query($query, $option)
                   ->update($data);
    }

    /**
     * 更新数据或添加数据
     *
     * @param       $query
     * @param array $where
     * @param array $data
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeLaravelUpdateOrCreate($query, $where = [], $data = [])
    {
        $table_fields = self::laravel_table_fields();
        $where        = yoo_array_remain_trim($where, $table_fields);
        $data         = yoo_array_remain_trim($data, $table_fields);
        return $query->updateOrCreate($where, $data);
    }

    /**
     * 删除数据
     *
     * @param       $query
     * @param array $option
     * @param bool  $bool
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeLaravelDelete($query, $option = [], $bool = true)
    {
        $arr_remain = self::laravel_where_option();
        $result     = self::laravel_query($query, $option, $arr_remain);
        if ($bool === true) {
            return $result->forceDelete();  //物理删除
        }
        else {
            return $result->delete();       //软删除
        }
    }




    /************ 直接调用 ************/

    /**
     * 参数设置
     *
     * [注] 设置['with', 'where', 'orwhere', 'wherein', 'wherenotin', 'wherehas', 'field', 'withcount', 'orderby']
     *
     *
     * @param       $query
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function lara_option($option = [])
    {
        $query  = self::laravel_model();
        $result = self::laravel_query($query, $option);
        return $result;
    }

    /**
     * 参数设置
     *
     * [注] 只设置['where', 'orWhere', 'whereIn', 'whereNotIn', 'whereHas']
     *
     * @param       $query
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function lara_where_option($option = [])
    {
        $query      = self::laravel_model();
        $arr_remain = self::laravel_where_option();
        $result     = self::laravel_query($query, $option, $arr_remain);
        return $result;
    }

    /**
     * 所有数据
     *
     * @param       $query
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function lara_all($option = [])
    {
        $query = self::laravel_model();
        return self::laravel_query($query, $option)
                   ->get();
    }

    /**
     * 获取列表数据-含分页
     * @param       $query
     * @param array $option
     *
     * @return mixed
     */
    public function lara_list($option = [])
    {
        $query = self::laravel_model();
        return self::laravel_query($query, $option)
                   ->paginate($option['limit']);
    }

    /**
     * 查询一条数据(通过主键id查询)
     *
     * @param       $query
     * @param int   $id
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function lara_find($id = 0, $option = [])
    {
        $query = self::laravel_model();

        //查询不存在的数据时，不返回null 最好使用 laravelOption()->find()
        return self::laravel_query($query, $option, ['with', 'field', 'withCount'])
                   ->find($id);
    }

    /**
     * 查询一条数据(通过where条件查询)
     *
     * @param       $query
     * @param array $option
     *
     * @return array
     */
    public function lara_one($option = [])
    {
        $query = self::laravel_model();
        return self::laravel_query($query, $option)
                   ->first();
    }

    /**
     * 获取统计数量
     *
     * @param       $query
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function lara_count($option = [])
    {
        $query = self::laravel_model();
        return self::laravel_query($query, $option)
                   ->count();
    }

    /**
     * 获取统计总和
     *
     * @param       $query
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function lara_sum($option = [])
    {
        $query = self::laravel_model();
        return self::laravel_query($query, $option)
                   ->sum($option['sum']);

    }

    /**
     * 添加一条数据
     *
     * @param       $query
     * @param array $data
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function lara_create($data = [])
    {
        $query = self::laravel_model();
        $data  = yoo_array_remain_trim($data, self::laravel_table_fields());
        return $query->create($data);
    }

    /**
     * 添加多条数据
     *
     * @param       $query
     * @param array $data
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function lara_insert($data = [])
    {
        $query = self::laravel_model();
        //TODO 处理字段，表中不存在的字段，自动去除
        //        $data = yoo_array_remain_trim($data, self::laravel_table_fields());
        return $query->insert($data);
    }

    /**
     * 更新数据
     *
     * @param       $query
     * @param array $data
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function lara_update($data = [], $option = [])
    {
        $query = self::laravel_model();
        $data  = yoo_array_remain_trim($data, self::laravel_table_fields());
        return self::laravel_query($query, $option)
                   ->update($data);
    }

    /**
     * 更新数据或添加数据
     *
     * @param array $where
     * @param array $data
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function lara_update_or_create($where = [], $data = [])
    {
        $query        = self::laravel_model();
        $table_fields = self::laravel_table_fields();
        $where        = yoo_array_remain_trim($where, $table_fields);
        $data         = yoo_array_remain_trim($data, $table_fields);
        return $query->updateOrCreate($where, $data);
    }

    /**
     * 删除数据
     *
     * @param       $query
     * @param array $option
     * @param bool  $bool
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function lara_delete($option = [], $bool = true)
    {
        $query      = self::laravel_model();
        $arr_remain = self::laravel_where_option();
        $result     = self::laravel_query($query, $option, $arr_remain);
        if ($bool === true) {
            return $result->forceDelete();  //物理删除
        }
        else {
            return $result->delete();       //软删除
        }
    }

    /* 软删除 */
    public function lara_del($option = [])
    {
        return self::lara_delete($option, false);
    }

    /* 物理删除 */
    public function lara_del_true($option = [])
    {
        return self::lara_delete($option, true);
    }


    /***************** 兼容代码 *****************/
    protected function tmp_where_option()
    {
        return self::laravel_where_option();
    }

    /**
     * 条件查询
     *
     * @param       $query
     * @param array $option
     * @param array $arr_remain
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    protected function tmp_laravel_query($query, &$option = [], $arr_remain = [])
    {
        return self::laravel_query($query, $option, $arr_remain);
    }

    /* 基础 option */
    protected function tmp_laravel_option($option = [])
    {
        return self::laravel_option($option);
    }


}
