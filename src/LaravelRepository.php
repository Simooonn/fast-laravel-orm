<?php
/**
 * Created by PhpStorm.
 * User: wumengmeng <wu_mengmeng@foxmail.com>
 * Date: 2019/4/26 0026
 * Time: 11:20
 */

namespace HashyooFast;

class LaravelRepository
{

    /**
     * 模型名称
     *
     * @return mixed
     */
    public function laravel_model()
    {
        $child_model_class = get_called_class();
        $model             = new $child_model_class;
        return $model->baseModel();
    }

    /**
     * 查询一条数据-根据主键查询
     * @param int   $id
     * @param array $arr_option
     *
     * @return mixed
     */
    public function find($id, $arr_option = [])
    {
        return self::laravel_model()
                   ->laravelFind($id, $arr_option);
    }

    /**
     * 查询一条数据-根据where条件查询
     * @param array $arr_option
     *
     * @return mixed
     */
    public function get_one($arr_option = [])
    {
        return self::laravel_model()
                   ->laravelOne($arr_option);
    }


    /**
     * 查询列表-含分页
     * @param array $arr_option
     *
     * @return mixed
     */
    public function get_list($arr_option = [])
    {
        return self::laravel_model()
                   ->laravelList($arr_option);
    }

    /**
     * 查询所有数据
     *
     * @param array $arr_option
     *
     * @return mixed
     */
    public function get_all($arr_option = [])
    {
        return self::laravel_model()
                   ->laravelAll($arr_option);
    }

    /**
     * 添加一条数据
     *
     * @param $post_data
     *
     * @return mixed
     */
    public function add_one($post_data = [])
    {
        $model     = self::laravel_model();
        $post_data = yoo_array_remain_trim($post_data, $model->laravel_table_fields());
        return $model->create($post_data); //返回插入数据
    }

    /**
     * 添加多条数据
     *
     * @param $post_data
     *
     * @return mixed
     */
    public function add_many($post_data = [])
    {
        return self::laravel_model()
                   ->insert($post_data);//返回 true 或 false
    }

    /**
     * 根据主键id修改一条数据
     *
     * @param array $update_data
     *
     * @return mixed
     */
    public function update($update_data = [], $arr_option = [])
    {
        return self::laravel_model()
                   ->laravelUpdate($update_data, $arr_option);//返回影响行数
    }

    /**
     * 通过属性找到相匹配的数据并更新，如果不存在即创建
     *
     * @param array $where
     * @param array $update_data
     *
     * @return mixed
     */
    public function update_or_create($where = [], $update_data = [])
    {
        return self::laravel_model()
                   ->updateOrCreate($where, $update_data); //返回插入或修改数据 只会影响或者插入一条数据
    }


    /**
     * 统计数量
     *
     * @param array $arr_option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function get_count($arr_option = [])
    {
        return self::laravel_model()
                   ->laravelCount($arr_option);
    }

    /**
     * 统计总和
     *
     * @param array $arr_option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function get_sum($arr_option = [])
    {
        return self::laravel_model()
                   ->laravelSum($arr_option);
    }


    /**
     * 根据主键id删除一条数据
     *
     * @param int  $id
     * @param bool $bool
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function delete_one($id = 0)
    {
        $arr_option = ['where' => ['id' => $id]];
        return self::laravel_model()
                   ->laravelDelete($arr_option, false);
    }

    /**
     * 根据主键id删除一条数据
     *
     * @param int  $id
     * @param bool $bool
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function del_one_true($id = 0)
    {
        $arr_option = ['where' => ['id' => $id]];
        return self::laravel_model()
                   ->laravelDelete($arr_option, true);
    }

    /**
     * 根据条件删除数据
     *
     * @param array $arr_option
     * @param bool  $bool
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function delete($arr_option = [])
    {
        return self::laravel_model()
                   ->laravelDelete($arr_option, false);
    }

    /**
     * 根据条件删除数据
     *
     * @param array $arr_option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function del_true($arr_option = [])
    {
        return self::laravel_model()
                   ->laravelDelete($arr_option, true);
    }


    /**
     * 字段自增 - 单条数据
     *
     * @param int    $id
     * @param string $field
     * @param int    $step
     *
     * @return mixed
     */
    public function increase_num($id, $field = 'sort', $step = 1)
    {
        return self::laravel_model()
                   ->where('id', $id)
                   ->increment($field, $step);
    }

    /**
     * 字段自减 - 单条数据
     *
     * @param int    $id
     * @param string $field
     * @param int    $step
     *
     * @return mixed
     */
    public function decrease_num($id, $field = 'sort', $step = 1)
    {
        return self::laravel_model()
                   ->where('id', $id)
                   ->decrement($field, $step);
    }


    /**
     * 字段自增 - 根据条件多条数据
     *
     * @param        $option
     * @param string $field
     * @param int    $step
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function incr_num($option, $field = 'sort', $step = 1)
    {
        $result = self::laravel_model()
                      ->laravelWhereOption($option)
                      ->increment($field, $step);
        return $result;
    }

    /**
     * 字段自减 - 根据条件多条数据
     *
     * @param        $option
     * @param string $field
     * @param int    $step
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function decr_num($option, $field = 'sort', $step = 1)
    {
        $result = self::laravel_model()
                      ->laravelWhereOption($option)
                      ->decrement($field, $step);
        return $result;
    }

}

