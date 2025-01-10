<?php

namespace App\Models\CustomModels;

class api_docs extends \App\Models\BasicModels\api_docs
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function custom_get_docs()
    {
        $data = [
            [
                'model' => '_',
                'title' => 'Token Statik',
                'desc' => 'Expired 1000 day',
                'type' => 'GET',
                'endpoint' => 'me',
                'body' => "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYTlmZjVmODQ0MjVlNmVkOWI2MzA3YzQ5ZGFlN2Y1MmViM2U2YjRiNzJkNzg0YjI2YzIzMjgyZDI0ZWM1ZTMzZWE0NmMzOGZjMTA0OThkMTEiLCJpYXQiOjE3MTM4MDIyMTYuMjgzNzkzLCJuYmYiOjE3MTM4MDIyMTYuMjgzNzk2LCJleHAiOjE3NDUzMzgyMTYuMjcxMTY5LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.z6EGNB1s5KMEhbVF2KE3Vnw6W7Qbk3lyTjJrDkiwc6mYnf2r2seLW4uB5EHFayHvB-mh5udKpWiIEEfsJFVowGzKma7xbdW1dCkNERi60JCXR5OhF06Bh6sENADHCsM0HzklfLBZJeigO2y1wZvaqR2uUppMIYB2jnm3jHKqT8FKUJITcfGpggF05Zl93k5gB0a_mobPCLz4wg8vqEIWVwm2B8Cxp3kmtxQjF9_ZcF8DGHe--a2xuFfdHoOPyQ4IT4GC2R1lsm-6G9A-q319MpVZyvEAMth62rjuGsihszlB6B_AFj-Qjk-5HEckg9r1XSRKscCCkb_VtWObwTI7ARUhTeozNNk7xL5WaGy0_FmQX57J6E2J97NHmXmmucJq8Dbj9b5mikRajxJ9dJ0kxHqfjX21kEQdJVA0-BloU4MoN0p7s2ad0squv5Ze_ePDETJ4dy8mOG-vYaiaH_wvvNiGeeqd76HFPU99Bj7LMGFpnwS59Ut0BzH99oTn7HBzRZBQEVOXfbUrg0mOwMyazE4dqq-eu1H7LKzkJlEww3Dzf-UAZSdrjXUsDG5nm-hDxWfITZukL5oEujg70XzI7P7mBSRFCGG-GxdUFWzzFw8X0MLXjUE_2qpX4MDAivsry5AyJbdR0zxVHT1tQ4oUe-ns7erJiHy_l210_0_nh3o",
            ],
            [
                'model' => 'm_product',
                'title' => 'Produk By Filter Search',
                'desc' => '-',
                'type' => 'GET',
                'endpoint' => 'm_product?searchfield=m_product.name,m_product_cat.name,m_product.desc&search=sate',
                'body' => "{}",
            ],
            [
                'model' => 'm_product',
                'title' => 'Produk Terlaris',
                'desc' => 'Order by produk terjual paling banyak',
                'type' => 'GET',
                'endpoint' => 'm_product?scopes=topOrder',
                'body' => "{}",
            ],
            [
                'model' => 'm_product',
                'title' => 'Produk By ID',
                'desc' => '-',
                'type' => 'GET',
                'endpoint' => 'm_product/1',
                'body' => "{}",
            ],
            [
                'model' => 'm_member',
                'title' => 'List Member',
                'desc' => '-',
                'type' => 'GET',
                'endpoint' => 'm_member',
                'body' => "{}",
            ],
            [
                'model' => 'm_member',
                'title' => 'Cari Member',
                'desc' => 'By Phone',
                'type' => 'POST',
                'endpoint' => 'm_member/find_member',
                'body' => '
{
    "no_hp" : "089677331038"
}
',
            ],
            [
                'model' => 't_order',
                'title' => 'Konfirmasi Order',
                'desc' => 'Lakukan validasi order sebelum proses checkou, karena proses checkout sudah masuk ke payment gateway',
                'type' => 'POST',
                'endpoint' => 't_order/order_validation',
                'body' => '
{
    "no_hp" : "089677331038",
    "orderer_name" : "Budi Santoso",
    "device_name" : "Xiamo Device 1",
    "t_order_det": [
        {
            "m_product_id": 1,
            "qty": 2
        },
        {
            "m_product_id": 2,
            "qty": 2
        }
    ]
}
                '
            ],
            [
                'model' => 't_order',
                'title' => 'Checkout',
                'desc' => '-',
                'type' => 'POST',
                'endpoint' => 't_order/order',
                'body' => '
{
    "payment_type" : "qris",
    "no_hp" : "089677331038",
    "orderer_name" : "Budi Santoso",
    "device_name" : "Xiamo Device 1",
    "t_order_det": [
        {
            "m_product_id": 1,
            "qty": 2
        },
        {
            "m_product_id": 2,
            "qty": 2
        }
    ]
}
                '
            ],
            [
                'model' => 't_order',
                'title' => 'Check Order',
                'desc' => 'Check order after checkout',
                'type' => 'GET',
                'endpoint' => 't_order/check_order?id=11',
                'body' => '{}'
            ],
            [
                'model' => 'm_survey',
                'title' => 'Survey',
                'desc' => 'Get List Survey',
                'type' => 'GET',
                'endpoint' => 'm_survey?where=this.is_active=true&selectfield=question,id',
                'body' => '{}'
            ],
            [
                'model' => 't_survey',
                'title' => 'Survei',
                'desc' => 'Kirim Survei',
                'type' => 'POST',
                'endpoint' => 't_survey/post',
                'body' => '
{
    "t_order_id" : 44,
    "t_survey" : [
        {
            "m_survey_id" : 1,
            "answer" : "sts"
        },
        {
            "m_survey_id" : 2,
            "answer" : "ts"
        },
        {
            "m_survey_id" : 3,
            "answer" : "c"
        },
        {
            "m_survey_id" : 4,
            "answer" : "s"
        },
        {
            "m_survey_id" : 5,
            "answer" : "st"
        }
    ]
}'
            ]
        ];

        return response($data);
    }
}