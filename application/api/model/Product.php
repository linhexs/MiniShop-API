<?php


namespace app\api\model;


use think\model\concern\SoftDelete;

class Product extends BaseModel
{
    use SoftDelete;
    protected $hidden = ['delete_time','create_time', 'update_time', 'from'];
    public $autoWriteTimestamp = true;//自动写入时间戳
    public function getMainImgUrlAttr($value, $data)
    {
        return $this->getUrlAttr($value, $data);
    }

    /**
     * @param $params
     * @return array
     * @throws \LinCmsTp5\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 获取商品列表
     */
    public static function getProductsPaginate($params){
        $product=[];
        if (array_key_exists('product_name', $params)) {
            $product[] = ['name', 'like', '%' . $params['product_name'] . '%'];
        }
        list($start,$count) = paginate();
        $productList = self::where($product);
        $totalNums = $productList->count();
        $productList = $productList->limit($start, $count)
            ->with('category,image.img,property')
            ->order('create_time desc')
            ->select();
        $result = [
            'collection'=>$productList,
            'total_nums'=>$totalNums
        ];
        return $result;
    }
    public function category(){
        return $this->belongsTo('Category');
    }
    public function image(){
        return $this->hasMany('ProductImage')->order('order');
    }
    public function property()
    {
        return $this->hasMany('ProductProperty');
    }
}