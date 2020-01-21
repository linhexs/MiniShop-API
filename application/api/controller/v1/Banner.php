<?php


namespace app\api\controller\v1;

use  app\api\model\Banner as BannerModel;
use app\api\model\BannerItem;
use app\lib\exception\banner\BannerExcetpion;
use think\Exception;
use think\facade\Request;
use \think\facade\Hook;
use think\model\concern\SoftDelete;

class Banner
{
    use SoftDelete;
    protected $hidden = ['delete_time', 'update_time'];

    /**
     * 获取banners
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getBanners()
    {
        $result = BannerModel::with('items.img')->select();
        return $result;
    }

    /**
     * 新增轮播图接口
     * 使用验证器注册
     * @validate('BannerForm')
     * @return \think\response\Json
     * @throws Exception
     */
    public function addBanner()
    {
        $params = Request::post();
        try {
            BannerModel::add($params);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), 500);
        }
        return writeJson("201", [], '新增成功！');
    }

    /**
     * @param('ids','待删除的轮播图id列表','require|array|min:1')
     * 删除banner
     * @return \think\response\Json
     */
    public function delBanner()
    {
        $ids = Request::delete('ids');
        array_map(function ($id) {
            // 查询指定id的轮播图记录
            $banner = BannerModel::get($id, 'items');
            // 指定id的轮播图不存在则抛异常
            if (!$banner) throw new BannerExcetpion(['msg' => 'id为' . $id . '的轮播图不存在']);
            // 执行关联删除
            $banner->together('items')->delete();
        }, $ids);
        Hook::listen('logger', '删除了id为' . implode(',', $ids) . '的轮播图');
        return writeJson(201, [], '轮播图删除成功！');
    }
    /**
     * 编辑轮播图基础信息
     * @param $id
     * @param('id','轮播图id','require|number')
     * @param('name','轮播图名称','require')
     */
    public function editBannerInfo($id)
    {
        $bannerInfo = Request::patch();
        $banner = BannerModel::get($id);
        if (!$banner) {
            throw new BannerExcetpion(
                ['msg' => 'id为' . $id . '的轮播图不存在']
            );
        }
        $banner->save($bannerInfo);
        return writeJson(201,'轮播图基础信息更新成功！');
    }
    /**
     * 新增轮播图元素
     * @validate('BannerItemForm.add')
     * @return \think\response\Json
     * @throws \LinCmsTp5\exception\ParameterException
     */
    public function addBannerItem()
    {
        $data = Request::post('items');

        foreach ($data as $key => $value) {
            BannerItem::create($value);
        }
        return writeJson(201, [], '新增轮播图元素成功！');
    }
    /**
     * 编辑轮播图元素
     * @validate('BannerItemForm.edit')
     * @throws \Exception
     */
    public function editBannerItem()
    {
        $data = Request::put('items');

        $bannerItem = new BannerItem();
        # allowField(true)表示只允许写入数据表中存在的字段。
        # saveAll()接收一个数组，用于批量更新或者新增。通过判断传入的数组中是否设置了id属性，如果有则视为更新，无则视为新增
        $bannerItem->allowField(true)->saveAll($data);
        return writeJson(201, [], '编辑轮播图元素成功！');
    }
    /**
     * 删除轮播图元素
     * @param('ids','待删除的轮播图元素id列表','require|array|min:1')
     */
    public function delBannerItem()
    {
        $ids = Request::delete('ids');
        // 传入多个id组成的数组进行批量删除
        BannerItem::destroy($ids);
        return writeJson(201, [], '轮播图元素删除成功！');
    }
}