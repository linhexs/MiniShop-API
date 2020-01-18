<?php


namespace app\api\model;


use app\lib\exception\banner\BannerExcetpion;
use think\Db;
use think\Exception;
use think\Model;
use think\model\concern\SoftDelete;

class Banner extends Model
{
    use SoftDelete;
    protected $hidden = ['delete_time', 'update_time'];

    /**
     * @param $parmas
     * @throws BannerExcetpion
     * 添加banner
     */
    public static function add($parmas){
        Db::startTrans();
        try{
        $banner = self::create($parmas, true);
        // 调用关联模型，实现关联写入；saveAll()方法用于批量新增数据
        $banner->items()->saveAll($parmas['items']);
        Db::commit();
        }catch (Exception $ex){
            Db::rollback();
            throw new BannerExcetpion([
                'msg' => '新增轮播图失败',
                'error_code' => 70001
            ]);
        }
    }
    public function items()
    {
        return $this->hasMany('BannerItem','banner_id', 'id');
    }
}