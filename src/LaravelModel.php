<?php

namespace HashyooFast;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class LaravelModel extends Model
{
    use SoftDeletes;


    private function laravel_child_model()
    {
        $child_model_class = get_called_class();
        return new $child_model_class;
    }

    /**
     * $option参数示例
     *
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    private function demo(){
        $option = [
          'with'=>['aa','bb'],
          'where'=>[
            'is_menu'=>1,//类型1
            ['status','!=',2],//类型2
            ['name','like','习俗'],//类型3
          ],
          'whereIn'=>[
            'role_id'=>[6,8,22],
            'user_name'=>['张三','李四']
          ],
          'whereNotIn'=>[
            'role_id'=>[6,8,22],
            'user_name'=>['张三','李四']
          ],
          'orWhere'=>[
            'is_menu'=>1,//类型1
            ['status','!=',2],//类型2
            ['name','like','习俗'],//类型3
          ],
          'field'=>['id','name','title'],
          'withCount'=>['aa','bb'],
          //order排序，谁在前谁优先
          'orderBy'=>[
            'sort'=>'asc',
            'id'=>'asc'
          ],
          'limit'=>15,
          'sum'=>'score',
          'whereHas'    => [
            'user.goods' => [
              'where'   => [
                'is_menu'=>1,//类型1
                ['status','!=',2],//类型2
                ['name','like','习俗'],//类型3
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
    protected function laravel_table_fields()
    {
        $prefix = DB::getConfig('prefix');
        $table = $this->laravel_child_model()->table;
        $s_full_table = $prefix . $table;
        $columns = DB::getDoctrineSchemaManager()->listTableColumns($s_full_table);
        $arr_data = [];
        foreach ($columns as $column) {
            $arr_data[] = $column->getName();
        }
        return $arr_data;
    }

    /* 基础 option */
    private function laravel_option($option = []) {
        $option = array_change_key_case($option,CASE_LOWER);
        $option['field'] = isset($option['field']) ? $option['field'] : ['*'];//默认查询字段
        $option['orderby'] = isset($option['orderby']) ? $option['orderby'] : [];//多个排序方式
        $option['limit'] = isset($option['limit']) ? $option['limit'] : 10;//默认每页查询条数
        return $option;
    }

    private function where_option(){
        return yoo_array_value_lower(['where', 'orWhere', 'whereIn', 'whereNotIn', 'whereHas']);
    }

    /* with */
    private function laravel_with($query, $arr_data) {
        $query = $query->with($arr_data);
        return $query;
    }

    /* where */
    private function laravel_where($query, $arr_data) {
        foreach ($arr_data as $key => $value) {
            if (is_array($value)) {
                if($value[1] == 'like'){
                    $query = $query->where($key, 'like', '%' . $value . '%');
                }
                else{
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
    private function laravel_orwhere($query, $arr_data) {
        foreach ($arr_data as $key => $value) {
            if (is_array($value)) {
                if($value[1] == 'like'){
                    $query = $query->orWhere($key, 'like', '%' . $value . '%');
                }
                else{
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
    private function laravel_wherein($query, $arr_data) {
        foreach ($arr_data as $key => $value) {
            if(!empty($value)){
                $query = $query->whereIn($key, $value);
            }
        }
        return $query;
    }

    /* whereNotIn */
    private function laravel_wherenotin($query, $arr_data) {
        foreach ($arr_data as $key => $value) {
            if(!empty($value)) {
                $query = $query->whereNotIn($key, $value);
            }
        }
        return $query;
    }

    /* select */
    private function laravel_select($query, $arr_data) {
        $query = $query->select($arr_data);
        return $query;
    }

    /* withCount */
    private function laravel_withcount($query, $arr_data) {
        $query = $query->withCount($arr_data);
        return $query;
    }

    /* orderBy */
    private function laravel_order($query, $arr_data) {
        foreach ($arr_data as $key => $value) {
            $query = $query->orderBy($key, $value);
        }
        return $query;
    }

    /* whereHas */
    private function laravel_wherehas($query, $arr_data) {
        foreach ($arr_data as $key => $value) {
            $query = $query->whereHas($key,
              function ($qqqqq)
              use ($value) {
                  $value = array_change_key_case($value, CASE_LOWER);

                  foreach ($value as $kk => $vv) {
                      switch ($kk) {
                          case 'where':
                              $qqqqq = $this->laravel_where($qqqqq,$vv);
                              break;
                          case 'orwhere':
                              $qqqqq = $this->laravel_orwhere($qqqqq,$vv);
                              break;
                          case 'wherein':
                              $qqqqq = $this->laravel_wherein($qqqqq,$vv);
                              break;
                          case 'wherenotin':
                              // 注：当关联关系是1对多时，whereNotIn效果有限，只有关联里有一个数据不符合whereNotIn条件，该数据仍然会显示
                              $qqqqq = $this->laravel_wherenotin($qqqqq,$vv);
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
    private function laravel_query($query, &$option = [],$arr_remain = [])
    {
        $option = $this->laravel_option($option);
        if(!empty($arr_remain)){
            $arr_remain = yoo_array_value_lower($arr_remain);
            $option = yoo_array_remain($option,$arr_remain);
        }

        $query = !empty($option['with']) ? $this->laravel_with($query, $option['with']) : $query;
        $query = !empty($option['where']) ? $this->laravel_where($query, $option['where']) : $query;
        $query = !empty($option['orwhere']) ? $this->laravel_orwhere($query, $option['orwhere']) : $query;
        $query = !empty($option['wherein']) ? $this->laravel_wherein($query, $option['wherein']) : $query;
        $query = !empty($option['wherenotin']) ? $this->laravel_wherenotin($query, $option['wherenotin']) : $query;
        $query = !empty($option['wherehas']) ? $this->laravel_wherehas($query, $option['wherehas']) : $query;
        $query = !empty($option['field']) ? $this->laravel_select($query, $option['field']) : $query;
        $query = !empty($option['withcount']) ? $this->laravel_withcount($query, $option['withcount']) : $query;
        $query = !empty($option['orderby']) ? $this->laravel_order($query, $option['orderby']) : $query;
        return $query;

    }

    public function scopeLaravelOption($query,$option = []){
        $result = $this->laravel_query($query,$option);
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
    public function scopeLaravelAll($query, $option = []) {
        return  $this->laravel_query($query,$option)->get();
    }

    /**
     * 获取列表数据-含分页
     * @param $query
     * @param array $option
     * @return mixed
     */
    public function scopeLaravelList($query, $option = []) {
        return $this->laravel_query($query,$option)->paginate($option['limit']);
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
    public function scopeLaravelFind($query, $id = 0, $option = []) {
        return $this->laravel_query($query,$option,['with','field','withCount'])->find($id);
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
    public function scopeLaravelUpdate($query,  $data = [],  $option = []) {
        return $this->laravel_query($query,$option)->update($data);
    }

    /**
     * 查询一条数据(通过where条件查询)
     * @param $query
     * @param array $option
     * @return array
     */
    public function scopeLaravelOne($query, $option = []) {
        return $this->laravel_query($query,$option)->first();
    }

    /**
     * 获取统计数量
     *
     * @param       $query
     * @param array $arr_option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeLaravelCount($query, $option = []) {
        return $this->laravel_query($query,$option)->count();
    }

    /**
     * 获取统计总和
     *
     * @param       $query
     * @param array $arr_option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function scopeLaravelSum($query, $option = [])
    {
        return $this->laravel_query($query,$option)->sum($option['sum']);

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
    public function scopeLaravelDelete($query,$option = [],  $bool = true)
    {
        $arr_remain = $this->where_option();
        $result = $this->laravel_query($query,$option,$arr_remain);
        if ($bool === true) {
            return $result->forceDelete();  //物理删除
        } else {
            return $result->delete();       //软删除
        }
    }



}
