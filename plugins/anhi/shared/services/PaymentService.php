<?php

namespace Anhi\Shared\Services;

use Auth, Input, DB, Hash, Response;

use Request;

use Anhi\Payment\Models\PaymentPackages as Package;

use Anhi\Payment\Models\Transactions;

use Helper;

class PaymentService
{
    function storeTransaction ($data)
    {
        try {

            $packageId = $data['packageId'];

            $package = Package::find($packageId);

            $user = Auth::getUser();

            $id = $this->getId($data);

            $insertData = [
                'transaction_id' => $id,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'package_id' => $packageId,
                'days' => $package->days,
                'money' => $package->price,
                'created_at' => (new \DateTime)->format('Y-m-d h:i:s'),
                'paygate_code' => $package->paygate_code,
                'paygate_name' => $package->paygate->paygate_name,
                'status' => 1
            ];
            
            $result = Transactions::insert($insertData);

            $this->insertVip($package->days, $package->price);

        } catch (\Exception $ex) {

            info($ex);

            $result = false;
        }

        return [
            'status' => $result,
            'id' => $id,
            'msg' => $result ? '' : 'Failed to create transaction. Please contact admin and provide paypal id: ' . ($id) . '. We are so sorry for this issue.'
        ];
    }

    function insertVip ($days, $price)
    {

        $user = Auth::getUser();

        $result = [
            "totalVipDays" => 0
        ];

        $expire = new \DateTime($user->expire);

        $now = new \DateTime;

        if ($expire < $now)
            $expire = $now;

        $expire->modify("+{$days} days");

        $user->money = (double)$user->money + $price;
        $user->expire = $expire;

        if (Helper::getDays($user->expire->format('Y-m-d h:i:s')) > 9)
            $user->send_notification = 0;

        $user->save();


        //insert vip
        $vipGroup = DB::table('user_groups')->where('code', 'vip')->first();

        \DB::table('users_groups')->where('user_id', $user->id)->delete();
        \DB::table('users_groups')->insert([
            'user_id' => $user->id,
            'user_group_id' => $vipGroup->id
        ]);

        $result['totalVipDays'] = ceil( (time() - strtotime($user->expire->format('Y-m-d h:i:s')))/60/60/24 );

        return $result;
    }


    function getHistories ($page = 1, $perPage = 10)
    {
        $user = Auth::getUser();

        $skip = (($page -1 ) *  $perPage);

        $result = Transactions::where('user_id', $user->id)
                                    ->orderBy('created_at', 'desc')
                                    ->skip($skip)
                                    ->take($perPage)
                                    ->get();

        return $result;
    }

    function paging ($page = 1, $perPage = 10)
    {
        $user = Auth::getUser();

        $query = Transactions::where('user_id', $user->id);

        return Helper::paging($query->count(), $perPage, $page);
    }
}