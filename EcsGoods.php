<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2016/7/14
 * Time: 10:52
 */

namespace common\models\yaofang;


use yii\db\Query;   //先引用这个   这个是sql语句的类
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * CampaignInfo model
 * wap站药品表
 * @property integer id
 * @property string title
 * @property integer style
 * @property integer type
 * @property string cat_refer
 * @property string top
 * @property string content
 * @property string bottom
 * @property string start_time
 * @property integer end_time
 * @property integer day_nb
 * @property integer limit_nb
 * @property string css
 * @property string js
 * @property integer status
 */
class EcsGoods extends ActiveRecord
{
    // 活动页面组合定义
    const STATUS_NEW      = 1;       // 单一页面活动，可有多个活动项目组成
    const MODE_TUMOUR     = 15008;   // 肿瘤药品  三级分类中的肿瘤辅助药物
    const MODE_SLOW_DISEASE = 3136;  // 慢性粒细胞白血病
    const MODE_MAN        = 43230;   // 男科用药
    const MODE_TOGETHER   = 7916;    // 综合营养 yf_category
    const MODEXTY_NEW  =45141;       // 新药、特药
    const MODE_CHILD_DEPAT = 2726;  //儿科用药(小儿咳嗽化痰药)
    const MODE_WOMEN      = 3842;   //妇科用药
    const MODE_SLOW       = 3136;  //慢性病

    // public $goods_name;

    public static function getDb()
    {
        // return Yii::$app->get('rdb');
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ecs_goods}}';
    }

     /**
     * @param $department_ID
     * @return null|static
     */
    public static function findQuerySetByID($department_ID)
    {
        return static::findOne($department_ID);
    }

    /**
     * Relational link for DepartmentClass
     */
    public function getDepartmentClass()
    {
        $this->hasOne(DepartmentClass::className(), ['id' => 'class']);   //hasOne是关联查询DepartmentClass::className() 这是表名，后面是条件
    }
//DepartmentClass::className() 这个是怎么来的   这个是引用另一个model  ::className()  调用这个方法 这个类哪里引入了  没引  没用到这个  要用就得引en 
    /**
     * 新药品推荐
     * @return null|static  //这块是查询 (new Query()) 实例化这个类  这些你应该读懂了吧？？ 懂  查询 findOne findAll() find  selsect
     */
    public static function findNewGoods() 
    {
        return (new Query())
            ->select('di.goods_id,di.goods_name,di.goods_img,pi.user_price')
            ->from(['di'=>self::tableName()]) //di是别名   yes
            ->leftJoin('ecs_member_price pi','di.goods_id = pi.goods_id') //di.goods_id = pi.goods_id 关联条件 恩
            ->where(['di.is_new' => self::STATUS_NEW]) //这个呢  字段啊  is_new这是那个表的字段 恩 那个表？写不写别名都行 因为这个字段唯一 可以！
            ->groupBy('pi.goods_id') //同上？  恩
            ->limit(12)
            ->all(); // findOne查询一条数据？ findAll 怎么理解   find呢 findAll  查询所有 find 就是查询  这块你可以上官网看看 恩   
    }

    /**
     * 新特药 药品推荐
     * @return null|static
     */
    public static function findxtyNewGoods()
    {
        return (new Query())
            ->select('di.goods_id,di.goods_name,di.goods_img,pi.user_price')
            ->from(['di'=>self::tableName()])
            ->leftJoin('ecs_member_price pi','di.goods_id = pi.goods_id')
            ->where(['cat_id' => self::MODEXTY_NEW])
            ->groupBy('goods_id')
            ->limit(12)
            ->all();
    }

    /**
     * 新特药 精品推荐
     * @return null|static
     */
    public static function findxtyCareRcommGoods()
    {
        return (new Query())
            ->select('di.goods_id,di.goods_name,di.goods_img,pi.user_price')
            ->from(['di'=>self::tableName()])
            ->leftJoin('ecs_member_price pi','di.goods_id = pi.goods_id')
            ->where(['cat_id' => self::MODEXTY_NEW])
            ->groupBy('goods_id')
            ->limit(4)
            ->all();
    }

    /**
     * 常见病 儿科用药
     * @return null|static
     */
    public static function findChildGoods()
    {
        return (new Query())
            ->select('di.goods_id,di.goods_name,di.goods_img,pi.user_price')
            ->from(['di'=>self::tableName()])
            ->leftJoin('ecs_member_price pi','di.goods_id = pi.goods_id')
            ->where(['cat_id' => self::MODE_CHILD_DEPAT])
            ->groupBy('goods_id')
            ->limit(4)
            ->all();
    }

    /**
     * 肿瘤药品
     * @return null|static
     */
    public static function findTumourGoods()
    {
        return (new Query())
            ->select('di.goods_id,di.goods_name,di.goods_img,pi.user_price')
            ->from(['di'=>self::tableName()])
            ->leftJoin('ecs_member_price pi','di.goods_id = pi.goods_id')
            ->where(['cat_id' => self::MODE_TUMOUR])
            ->groupBy('goods_id')
            ->limit(2)
            ->all();
    }

    /**
     * 慢性病药品
     * @return null|static
     */
    public static function findSlowGoods()
    {
        return (new Query())
            ->select('di.goods_id,di.goods_name,di.goods_img,pi.user_price')
            ->from(['di'=>self::tableName()])
            ->leftJoin('ecs_member_price pi','di.goods_id = pi.goods_id')
            ->where(['cat_id' => self::MODE_SLOW_DISEASE])
            ->groupBy('goods_id')
            ->limit(2)
            ->all();
    }

    /**
     * 慢性病 慢病药品
     * @return null|static
     */
    public static function findSlowsGoods()
    {
        return (new Query())
            ->select('di.goods_id,di.goods_name,di.goods_img,pi.user_price')
            ->from(['di'=>self::tableName()])
            ->leftJoin('ecs_member_price pi','di.goods_id = pi.goods_id')
            ->where(['cat_id' => self::MODE_SLOW_DISEASE])
            ->groupBy('goods_id')
            ->limit(4)
            ->all();
    }

    /**
     * 男科药品
     * @return null|static
     */
    public static function findManGoods()
    {
        return (new Query())
            ->select('di.goods_id,di.goods_name,di.goods_img,pi.user_price')
            ->from(['di'=>self::tableName()])
            ->leftJoin('ecs_member_price pi','di.goods_id = pi.goods_id')
            ->where(['cat_id' => self::MODE_MAN])
            ->groupBy('goods_id')
            ->limit(2)
            ->all();
    }

    /**
     * 妇科药品
     * @return null|static
     */
    public static function findWomanGoods()
    {
        return (new Query())
            ->select('di.goods_id,di.goods_name,di.goods_img,pi.user_price')
            ->from(['di'=>self::tableName()])
            ->leftJoin('ecs_member_price pi','di.goods_id = pi.goods_id')
            ->where(['cat_id' => self::MODE_WOMEN])
            ->groupBy('goods_id')
            ->limit(4)
            ->all();
    }

    /**
     * 综合营养药品
     * @return null|static
     */
    public static function findTogetherGoods()
    {
        return (new Query())
            ->select('di.goods_id,di.goods_name,di.goods_img,pi.user_price')
            ->from(['di'=>self::tableName()])
            ->leftJoin('ecs_member_price pi','di.goods_id = pi.goods_id')
            ->where(['cat_id' => self::MODE_TOGETHER])
            ->limit(2)
            ->groupBy('goods_name')
            ->all();
    }

    /**
     * 二级页面 肿瘤药品
     * @return null|static
     */
    public static function findzlTumourGoods($id)
    {
        return (new Query())
            ->select('di.goods_id,di.goods_name,di.goods_img,pi.user_price')
            ->from(['di'=>self::tableName()])
            ->leftJoin('ecs_member_price pi','di.goods_id = pi.goods_id')
            ->where(['cat_id' => $id])
            ->groupBy('goods_id')
            ->limit(6)
            ->all();
    }

    /**
     * 资讯热销推荐（终端页面）
     * @return null|static
     */
    public static function findzxHot($cat_id)
    {
        return (new Query())
            ->select('goods_id,goods_name,goods_img,give_integral')
            ->from(self::tableName())
            ->where(['cat_id' => $cat_id])
            ->limit(12)
            ->all();
    }

    /**
     * 查找某个分类的药品(视频播放相关药品)
     * @return null|static
     */
    public static function findLinkGoods($id)
    {
        return (new Query())
            ->select('di.goods_id,di.goods_name,di.goods_img,pi.user_price')
            ->from(['di'=>self::tableName()])
            ->leftJoin('ecs_member_price pi','di.goods_id = pi.goods_id')
            ->leftJoin('yf_goods_dealer gi','di.goods_id = gi.goods_id')
            ->where(['cat_id' => $id,'gi.is_on_sale' => 1])
            ->limit(4)
            ->groupBy('goods_id')
            ->all();
    }

    /**
     * 查找某个分类的药品(视频播放相关药品)
     * @return null|static
     */
    public static function findLinkGood($id)
    {
        return (new Query())
            ->select('di.goods_id,di.goods_name,di.goods_img,pi.user_price')
            ->from(['di'=>self::tableName()])
            ->leftJoin('ecs_member_price pi','di.goods_id = pi.goods_id')
            ->leftJoin('yf_goods_dealer gi','di.goods_id = gi.goods_id')
            ->where(['cat_id' => $id,'gi.is_on_sale' => 1])
            ->limit(12)
            ->groupBy('goods_id')
            ->all();
    }
}