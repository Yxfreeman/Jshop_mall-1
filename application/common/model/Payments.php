<?php
namespace app\common\model;

class Payments extends Common
{

    const PAYMENT_STATUS_YES = 1;      //启用
    const PAYMENT_STATUS_NO  = 2;      //禁用

    const PAYMENT_ONLINE = 1;           //线上支付
    const PAYMENT_OFFLINE = 2;          //线下支付


    //判断支付方式是否可用
    public function enable($payment_code)
    {
        return Payments::where(array('payment_code' => $payment_code))->find();
    }


    /**
     *  添加支付方式
     * User:tianyu
     * @param array $data
     * @return array
     */
    public function editData($data)
    {
        $result = ['status' => true, 'msg'   => '保存失败', 'data'  => ''];
        $data['params'] = json_encode($data['params']);
        if(isset($data['status']) && $data['status'] == 1){
        }else{
            $data['status'] = 2;
        }
        //如果已经添加，那么就给他更新
        $where['code'] = $data['code'];

        if($this->allowField(true)->save($data,$where)){
            $result['status'] = true;
            $result['msg'] = '保存成功';
        }
        return $result;

    }
    /**
     *
     * 快捷更改状态
     * @param $id
     * @param $status
     * @return array
     */
    public function changeStatus($id,$status)
    {
        $result = ['status'=>true,'msg'=>'','data'=>''];
        $where['id'] = $id;

        switch($status)
        {
            case 'true':
                $this->save(['status'=>self::PAYMENT_STATUS_YES],$where);
                $result['msg'] = '已'.config('params.payments')['status'][self::PAYMENT_STATUS_YES];
                break;
            case 'false':
                $this->save(['status'=>self::PAYMENT_STATUS_NO],$where);
                $result['msg'] = '已'.config('params.payments')['status'][self::PAYMENT_STATUS_NO];
                break;
            default:
                $result['status'] = false;
                $result['msg'] = '非法操作';
                break;
        }

        return $result;
    }

    protected function tableFormat($list)
    {
        foreach($list as $key => $val)
        {
            $list[$key]['is_online'] = config('params.payments')['is_online'][$val['is_online']];;
        }
        return $list;
    }


}