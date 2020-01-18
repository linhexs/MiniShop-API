<?php
/**
 * Created by: PhpStorm
 * User: lin
 * Date: 2019/10/6
 * Time: 19:29
 */

namespace app\api\controller\v1;


use app\api\model\ProductImage;
use app\api\model\ProductProperty;
use app\lib\exception\product\ProductException;
use think\Exception;
use think\facade\Hook;
use think\facade\Request;
use app\api\model\Product as productModel;

class Product
{
    /**
     * 查询所有商品，分页
     * @param('page','查询页码','require|number')
     * @param('count','单页查询数量','require|number|between:1,15')
     */
    public function getProductsPaginate()
    {
        $params = Request::get();
        $products = productModel::getProductsPaginate($params);
        if ($products['total_nums'] === 0) {
            throw new ProductException([
                'code' => 404,
                'msg' => '未查询到相关商品',
                'error_code' => '70006'
            ]);
        }
        return $products;
    }

    /**
     * @return array|\PDOStatement|string|\think\Collection
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 获取所有可用商品
     */
    public function getProducts()
    {
        $products = ProductModel::where('status', 1)->select();
        if ($products->isEmpty()) {
            throw new Exception([
                'code' => 404,
                'msg' => '未查询到相关商品'
            ]);
        }
        return $products;
    }

    /**
     * 商品上架/下架
     * @auth('商品上架/下架','商品管理')
     * @param('id','商品id','require|number')
     */
    public function modifyStatus($id)
    {
        $product = ProductModel::get($id);
        if (!$product) {
            throw new ProductException([
                'code' => 404,
                'msg' => '未查询到相关商品',
                'error_code' => '70006'
            ]);
        }
        $product->status = !$product->status;
        $product->save();
        return writeJson(201, [], '状态已经修改');
    }

    /**
     * 新增商品
     * @validate('ProductForm')
     */
    public function addProduct()
    {
        $params = Request::post();
        $params['main_img_url'] = explode(config('setting.img_prefix'), $params['main_img_url'])[1];
        $product = ProductModel::create($params, true);
        if (!$product) {
            throw new ProductException([
                'msg' => '商品创建失败'
            ]);
        }
        $product->image()->saveAll($params['image']);
        $product->property()->saveAll($params['property']);
        return writeJson(201, [], '商品新增成功');
    }

    /**
     * @return \think\response\Json
     * 删除商品
     */
    public function delProduct()
    {
        $ids = Request::delete('ids');
        array_map(function ($id) {

            $product = ProductModel::get($id, 'image,property');
            if ($product) {
                $product->together('image,property')->delete();
            }
        }, $ids);
        Hook::listen('logger', '删除了id为' . implode(',', $ids) . '的商品');
        return writeJson(201, [], '商品删除成功');
    }
    /**
     * 更新商品基础信息
     * @validate('ProductForm.edit')
     */
    public function updateProduct()
    {
        $params = Request::put();
        $params['main_img_url'] = explode(config('setting.img_prefix'), $params['main_img_url'])[1];
        ProductModel::update($params);
        return writeJson(201, '商品信息更新成功');
    }

    /**
     * @return \think\response\Json
     * @throws \Exception
     * 添加商品详情图
     * @validate('ProductImageForm')
     */
    public function addProductImage(){
        $params = Request::post('image');
        (new ProductImage())->saveAll($params);
        return writeJson('201','商品详情图新增成功');
    }

    /**
     * @return \think\response\Json
     * 编辑商品详情图
     * @validate('ProductImageForm.edit')
     */
    public function updateProductImage(){
        $params = Request::put('image');
        (new ProductImage())->saveAll($params);
        return writeJson(201, '商品详情图编辑成功');
    }
    /**
     * @return \think\response\Json
     * 删除商品详情图
     * @param('ids','待删除的商品详情图id列表','require|array|min:1')
     */
    public function delProductImage(){
        $ids = Request::delete('ids');
        ProductImage::destroy($ids);
        return writeJson(201, '商品详情图删除成功');
    }
    /**
     * 添加商品的商品属性
     * @validate('ProductPropertyForm')
     * @return \think\response\Json
     * @throws \Exception
     */
    public function addProductProperty()
    {
        $params = Request::post('property');
        (new ProductProperty())->saveAll($params);

        return writeJson(201, '商品属性新增成功');
    }

    /**
     * 编辑商品属性
     * @validate('ProductPropertyForm.edit')
     * @return \think\response\Json
     * @throws \Exception
     */
    public function updateProductProperty()
    {
        $params = Request::put('property');
        (new ProductProperty())->saveAll($params);
        return writeJson(201, '商品属性编辑成功');
    }
    /**
     * 删除商品属性
     * @param('ids','待删除的商品属性id列表','require|array|min:1')
     * @return \think\response\Json
     */
    public function delProductProperty()
    {
        $ids = Request::delete('ids');
        ProductProperty::destroy($ids);
        return writeJson(201, '商品属性删除成功');
    }
}