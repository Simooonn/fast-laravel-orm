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
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function laravel_model()
    {
        $model_class = get_called_class();
        $model             = new $model_class;
        return $model->baseModel();
    }

    /**
     * 根据主键查询1条数据
     *
     * @param       $id
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function find($id, $option = [])
    {
        return self::laravel_model()
                   ->laravelFind($id, $option);
    }

    /**
     * 根据条件查询1条数据
     *
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function get_one($option = [])
    {
        return self::laravel_model()
                   ->laravelOne($option);
    }


    /**
     * 根据条件获取分页数据
     *
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function get_list($option = [])
    {
        return self::laravel_model()
                   ->laravelList($option);
    }

    /**
     * 根据条件获取全部数据
     *
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function get_all($option = [])
    {
        return self::laravel_model()
                   ->laravelAll($option);
    }

    /**
     * 添加一条数据
     *
     * @param array $data
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function add_one($data = [])
    {
        $model     = self::laravel_model();
        $data = yoo_array_remain_trim($data, $model->laravel_table_fields());
        return $model->create($data); //返回插入数据
    }

    /**
     * 添加多条数据
     *
     * @param array $data
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function add_many($data = [])
    {
        return self::laravel_model()
                   ->insert($data);//返回 true 或 false
    }

    /**
     * 根据条件修改数据
     *
     * @param array $data
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function update($data = [], $option = [])
    {
        return self::laravel_model()
                   ->laravelUpdate($data, $option);//返回影响行数
    }

    /**
     * 通过条件找到相匹配的数据并更新，如果不存在即创建
     *
     * @param array $where
     * @param array $data
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function update_or_create($where = [], $data = [])
    {
        return self::laravel_model()
                   ->updateOrCreate($where, $data); //返回插入或修改数据 只会影响或者插入一条数据
    }


    /**
     * 统计数量
     *
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function get_count($option = [])
    {
        return self::laravel_model()
                   ->laravelCount($option);
    }

    /**
     * 统计总和
     *
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function get_sum($option = [])
    {
        return self::laravel_model()
                   ->laravelSum($option);
    }


    /**
     * 根据主键id删除一条数据-软删除
     *
     * @param int $id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function delete_one($id = 0)
    {
        $option = ['where' => ['id' => $id]];
        return self::laravel_model()
                   ->laravelDelete($option, false);
    }

    /**
     * 根据主键id删除一条数据-物理删除
     *
     * @param int $id
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function del_one_true($id = 0)
    {
        $option = ['where' => ['id' => $id]];
        return self::laravel_model()
                   ->laravelDelete($option, true);
    }

    /**
     * 根据条件删除数据-软删除
     *
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function delete($option = [])
    {
        return self::laravel_model()
                   ->laravelDelete($option, false);
    }

    /**
     * 根据条件删除数据-物理删除
     *
     * @param array $option
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function del_true($option = [])
    {
        return self::laravel_model()
                   ->laravelDelete($option, true);
    }


    /**
     *
     * 单条数据字段自增
     *
     * @param int    $id        主键id
     * @param string $field     字段名
     * @param int    $step      自增值
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function increase_num($id, $field = 'sort', $step = 1)
    {
        return self::laravel_model()
                   ->where('id', $id)
                   ->increment($field, $step);
    }

    /**
     * 单条数据字段自减
     *
     * @param int    $id        主键id
     * @param string $field     字段名
     * @param int    $step      自减值
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function decrease_num($id, $field = 'sort', $step = 1)
    {
        return self::laravel_model()
                   ->where('id', $id)
                   ->decrement($field, $step);
    }


    /**
     * 根据条件多条数据字段自增
     *
     * @param array  $option
     * @param string $field     字段名
     * @param int    $step      自增值
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function incr_num($option = [], $field = 'sort', $step = 1)
    {
        $result = self::laravel_model()
                      ->laravelWhereOption($option)
                      ->increment($field, $step);
        return $result;
    }

    /**
     * 根据条件多条数据字段自减
     *
     * @param array  $option
     * @param string $field     字段名
     * @param int    $step      自减值
     *
     * @return mixed
     * @author wumengmeng <wu_mengmeng@foxmail.com>
     */
    public function decr_num($option = [], $field = 'sort', $step = 1)
    {
        $result = self::laravel_model()
                      ->laravelWhereOption($option)
                      ->decrement($field, $step);
        return $result;
    }

}

