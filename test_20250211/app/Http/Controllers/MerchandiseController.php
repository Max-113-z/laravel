<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Hash;

use App\Shop\Entity\User;

use Illuminate\Support\Facades\Mail;

use App\Shop\Entity\Merchandise;

class MerchandiseController extends Controller
{
    #產品create
    public function MerchandiseCreateProcess()
    {
        $merchandise_data = [
            'status' => 'C',
            'name' => '',
            'name_en' => '',
            'introduction' => '',
            'introduction_en' => '',
            'photo' => '',
            'price' => 0,
            'remain_count' => 0,
        ];

        $merchandise_sql_data = Merchandise::create($merchandise_data);
        // dd($merchandise_sql_data );

        return redirect('/admin/merchandise/' . $merchandise_sql_data['id'] . '/edit');
    }
    #產品edit
    public function MerchandiseEditPage($merchandise_id)
    {
        $merchandises = Merchandise::where('id', $merchandise_id);
        if ($merchandises->count() === 0) {
            return redirect('/');
        } else {
            $merchandise = $merchandises->first();
            $binding = [
                'title' => '編輯商品',
                'merchandise' => $merchandise
            ];
            return view('merchandise.edit', $binding);
        }
    }
    #產品edit
    public function MerchandiseEditProcess($merchandise_id)
    {
        $input = request()->all();
        unset($input['_token']);
        // 
        if (isset($input['photo'])) {
            // 有上傳圖片
            $photo = $input['photo'];
            // 檔案副檔名
            $file_extension = $photo->getClientOriginalExtension();
            // 產生自訂隨機檔案名稱
            $file_name = uniqid() . '.' . $file_extension;
            // 檔案相對路徑
            $file_relative_path = 'images/merchandise/';
            // 檔案存放目錄為對外公開 public 目錄下的相對位置
            $file_path = public_path($file_relative_path);
             // 移動上傳檔案
             $photo->move($file_path, $file_name);
             // 設定存入資料庫的檔案路徑 是相對路徑
             $input['photo'] = $file_relative_path . $file_name;
        }
        Merchandise::where('id', $merchandise_id)
            ->update($input);
        return redirect('/admin/merchandise/' . $merchandise_id . '/edit');
    } 
    #產品manage
    public function MerchandiseManagePage()
    {
        // 獲取所有商品
        $merchandises = Merchandise::get();
    
        // 傳遞資料到視圖
        $binding = [
            'title' => '管理商品',
            'merchandises' => $merchandises
        ];
        
        return view('merchandise.manage', $binding);
        
    }
    #產品刪除
    public function destroy($id)
    {
        // 根據 ID 找到該商品
        
        $merchandise = Merchandise::find($id);  // 使用 $merchandise 來接收
    
        // 檢查商品是否存在
        if (!$merchandise) {
            return redirect('/admin/merchandise/manage')->with('error', '找不到該商品');
        }
    
        // 刪除商品
        $merchandise->delete();
    
        // 刪除後重新導向並顯示成功訊息
        return redirect('/admin/merchandise/manage')->with('success', '商品已成功刪除');
    }
    #產品端
    public function MerchandiseMaineCoonPage()
    {
        $merchandises = Merchandise::get();
        $binding = [
            'title' => '成貓介紹',
            'merchandises' => $merchandises
        ];
        return view('merchandise.mainecoon', $binding);
    }
    #產品manage
    public function MerchandiseIndexPage()
    {
        // 獲取所有商品
        $merchandises = Merchandise::all();
    
       
        return view('merchandise.index');
        
    }
    public function ReturnMerchandiseData()
    {
        $merchandises = Merchandise::get();
        return response()->jason([
            'status' =>0,
            'msg'=>'',
            'data'=>$merchandises

        ]);
    }
    public function MerchandiseKittyPage()
    {
        // 獲取所有商品
        $merchandises = Merchandise::all();
    
       
        return view('merchandise.kitty');
        
    }
}


