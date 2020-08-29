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
     * @return mixed
     */
    public function laravel_child_model()
    {
        $child_model_class = get_called_class();
        $model = new $child_model_class;
        return $model->baseModel();
    }

    /**
     * 查询一条数据-根据主键查询
     * @param int $id
     * @param array $arr_option
     * @return mixed
     */
    public function find( $id, $arr_option = [])
    {
        return $this->laravel_child_model()->laravelFind($id, $arr_option);
    }

    /**
     * 查询一条数据-根据where条件查询
     * @param array $arr_option
     * @return mixed
     */
    public function get_one($arr_option = [])
    {
        return $this->laravel_child_model()->laravelOne($arr_option);
    }


    /**
     * 查询列表-含分页
     * @param array $arr_option
     * @return mixed
     */
    public function get_list($arr_option = [])
    {
        return $this->laravel_child_model()->laravelList($arr_option);
    }

    /**
     * 查询所有数据
     * @param array $arr_option
     * @return mixed
     */
    public function get_all($arr_option = [])
    {
        return $this->laravel_child_model()->laravelAll($arr_option);
    }

    /**
     * 添加一条数据
     * @param $post_data
     * @return mixed
     */
    public function add_one($post_data = [])
    {
        $model = $this->laravel_child_model();
        $post_data = yoo_array_remain_trim($post_data, $model->laravel_table_fields());
        return $model->create($post_data);
    }

    /**
     * 添加多条数据
     * @param $post_data
     * @return mixed
     */
    public function add_many($post_data = [])
    {
        return $this->laravel_child_model()->insert($post_data);
    }

    /**
     * 根据主键id修改一条数据
     * @param array $update_data
     * @return mixed
     */
    public function update($update_data = [],$arr_option = [])
    {
        return $this->laravel_child_model()->laravelUpdate($update_data,$arr_option);
    }

    /**
     * 通过属性找到相匹配的数据并更新，如果不存在即创建
     * @param array $where
     * @param array $update_data
     * @return mixed
     */
    public function update_or_create($where = [], $update_data = [])
    {
        return $this->laravel_child_model()->updateOrCreate($where, $update_data);
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
        return $this->laravel_child_model()->laravelCount($arr_option);
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
        return $this->laravel_child_model()->laravelSum($arr_option);
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
    public function delete_one( $id = 0,  $bool = false)
    {
        $arr_option = ['where'=>['id'=>$id]];
        return $this->laravel_child_model()->laravelDelete($arr_option, $bool);
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
    public function delete($arr_option = [],  $bool = false)
    {
        return $this->laravel_child_model()->laravelDelete($arr_option, $bool);
    }


    /**
     * 字段自增
     * @param int $id
     * @param string $field
     * @param int $step
     * @return mixed
     */
    public function increaseNum( $id, $field = 'sort', $step = 1)
    {
        return $this->laravel_child_model()->where('id', $id)->increment($field, $step);
    }

    /**
     * 字段自减
     * @param int $id
     * @param string $field
     * @param int $step
     * @return mixed
     */
    public function decreaseNum( $id, $field = 'sort', $step = 1)
    {
        return $this->laravel_child_model()->where('id', $id)->decrement($field, $step);
    }

}