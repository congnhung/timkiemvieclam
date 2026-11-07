<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Danh mục 34 Tỉnh / Thành phố Việt Nam (Nghị quyết 202/2025/QH15)
    |--------------------------------------------------------------------------
    | Tinh gọn từ 63 tỉnh/thành xuống còn 34 đơn vị hành chính cấp tỉnh
    | Gồm 6 thành phố trực thuộc Trung ương và 28 tỉnh.
    | Có trường 'merged_from' để hỗ trợ tìm kiếm cả các tỉnh cũ đã sáp nhập.
    */
    'provinces' => [
        // 6 Thành phố trực thuộc Trung ương
        [
            'id' => '01',
            'code' => 'HN',
            'name' => 'Hà Nội',
            'name_en' => 'Ha Noi',
            'full_name' => 'Thành phố Hà Nội',
            'full_name_en' => 'Ha Noi City',
            'type' => 'Thành phố',
            'merged_from' => ['Hà Nội']
        ],
        [
            'id' => '02',
            'code' => 'HUE',
            'name' => 'Huế',
            'name_en' => 'Hue',
            'full_name' => 'Thành phố Huế',
            'full_name_en' => 'Hue City',
            'type' => 'Thành phố',
            'merged_from' => ['Huế', 'Thừa Thiên Huế']
        ],
        [
            'id' => '03',
            'code' => 'HP',
            'name' => 'Hải Phòng',
            'name_en' => 'Hai Phong',
            'full_name' => 'Thành phố Hải Phòng',
            'full_name_en' => 'Hai Phong City',
            'type' => 'Thành phố',
            'merged_from' => ['Hải Phòng', 'Hải Dương']
        ],
        [
            'id' => '04',
            'code' => 'DN',
            'name' => 'Đà Nẵng',
            'name_en' => 'Da Nang',
            'full_name' => 'Thành phố Đà Nẵng',
            'full_name_en' => 'Da Nang City',
            'type' => 'Thành phố',
            'merged_from' => ['Đà Nẵng', 'Quảng Nam']
        ],
        [
            'id' => '05',
            'code' => 'CT',
            'name' => 'Cần Thơ',
            'name_en' => 'Can Tho',
            'full_name' => 'Thành phố Cần Thơ',
            'full_name_en' => 'Can Tho City',
            'type' => 'Thành phố',
            'merged_from' => ['Cần Thơ', 'Sóc Trăng', 'Hậu Giang']
        ],
        [
            'id' => '06',
            'code' => 'HCM',
            'name' => 'Hồ Chí Minh',
            'name_en' => 'Ho Chi Minh',
            'full_name' => 'Thành phố Hồ Chí Minh',
            'full_name_en' => 'Ho Chi Minh City',
            'type' => 'Thành phố',
            'merged_from' => ['Hồ Chí Minh', 'TP.HCM', 'TPHCM', 'Bình Dương', 'Bà Rịa', 'Vũng Tàu']
        ],

        // 28 Tỉnh
        [
            'id' => '07',
            'code' => 'CB',
            'name' => 'Cao Bằng',
            'name_en' => 'Cao Bang',
            'full_name' => 'Tỉnh Cao Bằng',
            'full_name_en' => 'Cao Bang Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Cao Bằng']
        ],
        [
            'id' => '08',
            'code' => 'DB',
            'name' => 'Điện Biên',
            'name_en' => 'Dien Bien',
            'full_name' => 'Tỉnh Điện Biên',
            'full_name_en' => 'Dien Bien Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Điện Biên']
        ],
        [
            'id' => '09',
            'code' => 'HT',
            'name' => 'Hà Tĩnh',
            'name_en' => 'Ha Tinh',
            'full_name' => 'Tỉnh Hà Tĩnh',
            'full_name_en' => 'Ha Tinh Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Hà Tĩnh']
        ],
        [
            'id' => '10',
            'code' => 'LC',
            'name' => 'Lai Châu',
            'name_en' => 'Lai Chau',
            'full_name' => 'Tỉnh Lai Châu',
            'full_name_en' => 'Lai Chau Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Lai Châu']
        ],
        [
            'id' => '11',
            'code' => 'LS',
            'name' => 'Lạng Sơn',
            'name_en' => 'Lang Son',
            'full_name' => 'Tỉnh Lạng Sơn',
            'full_name_en' => 'Lang Son Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Lạng Sơn']
        ],
        [
            'id' => '12',
            'code' => 'NA',
            'name' => 'Nghệ An',
            'name_en' => 'Nghe An',
            'full_name' => 'Tỉnh Nghệ An',
            'full_name_en' => 'Nghe An Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Nghệ An']
        ],
        [
            'id' => '13',
            'code' => 'QN',
            'name' => 'Quảng Ninh',
            'name_en' => 'Quang Ninh',
            'full_name' => 'Tỉnh Quảng Ninh',
            'full_name_en' => 'Quang Ninh Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Quảng Ninh']
        ],
        [
            'id' => '14',
            'code' => 'SL',
            'name' => 'Sơn La',
            'name_en' => 'Son La',
            'full_name' => 'Tỉnh Sơn La',
            'full_name_en' => 'Son La Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Sơn La']
        ],
        [
            'id' => '15',
            'code' => 'TH',
            'name' => 'Thanh Hóa',
            'name_en' => 'Thanh Hoa',
            'full_name' => 'Tỉnh Thanh Hóa',
            'full_name_en' => 'Thanh Hoa Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Thanh Hóa']
        ],
        [
            'id' => '16',
            'code' => 'TQ',
            'name' => 'Tuyên Quang',
            'name_en' => 'Tuyen Quang',
            'full_name' => 'Tỉnh Tuyên Quang',
            'full_name_en' => 'Tuyen Quang Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Tuyên Quang', 'Hà Giang']
        ],
        [
            'id' => '17',
            'code' => 'LCA',
            'name' => 'Lào Cai',
            'name_en' => 'Lao Cai',
            'full_name' => 'Tỉnh Lào Cai',
            'full_name_en' => 'Lao Cai Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Lào Cai', 'Yên Bái']
        ],
        [
            'id' => '18',
            'code' => 'TN',
            'name' => 'Thái Nguyên',
            'name_en' => 'Thai Nguyen',
            'full_name' => 'Tỉnh Thái Nguyên',
            'full_name_en' => 'Thai Nguyen Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Thái Nguyên', 'Bắc Kạn']
        ],
        [
            'id' => '19',
            'code' => 'PT',
            'name' => 'Phú Thọ',
            'name_en' => 'Phu Tho',
            'full_name' => 'Tỉnh Phú Thọ',
            'full_name_en' => 'Phu Tho Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Phú Thọ', 'Vĩnh Phúc', 'Hòa Bình']
        ],
        [
            'id' => '20',
            'code' => 'BN',
            'name' => 'Bắc Ninh',
            'name_en' => 'Bac Ninh',
            'full_name' => 'Tỉnh Bắc Ninh',
            'full_name_en' => 'Bac Ninh Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Bắc Ninh', 'Bắc Giang']
        ],
        [
            'id' => '21',
            'code' => 'HY',
            'name' => 'Hưng Yên',
            'name_en' => 'Hung Yen',
            'full_name' => 'Tỉnh Hưng Yên',
            'full_name_en' => 'Hung Yen Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Hưng Yên', 'Thái Bình']
        ],
        [
            'id' => '22',
            'code' => 'NB',
            'name' => 'Ninh Bình',
            'name_en' => 'Ninh Binh',
            'full_name' => 'Tỉnh Ninh Bình',
            'full_name_en' => 'Ninh Binh Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Ninh Bình', 'Hà Nam', 'Nam Định']
        ],
        [
            'id' => '23',
            'code' => 'QT',
            'name' => 'Quảng Trị',
            'name_en' => 'Quang Tri',
            'full_name' => 'Tỉnh Quảng Trị',
            'full_name_en' => 'Quang Tri Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Quảng Trị', 'Quảng Bình']
        ],
        [
            'id' => '24',
            'code' => 'QNG',
            'name' => 'Quảng Ngãi',
            'name_en' => 'Quang Ngai',
            'full_name' => 'Tỉnh Quảng Ngãi',
            'full_name_en' => 'Quang Ngai Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Quảng Ngãi']
        ],
        [
            'id' => '25',
            'code' => 'BD',
            'name' => 'Bình Định',
            'name_en' => 'Binh Dinh',
            'full_name' => 'Tỉnh Bình Định',
            'full_name_en' => 'Binh Dinh Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Bình Định', 'Gia Lai']
        ],
        [
            'id' => '26',
            'code' => 'KH',
            'name' => 'Khánh Hòa',
            'name_en' => 'Khanh Hoa',
            'full_name' => 'Tỉnh Khánh Hòa',
            'full_name_en' => 'Khanh Hoa Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Khánh Hòa', 'Ninh Thuận']
        ],
        [
            'id' => '27',
            'code' => 'LD',
            'name' => 'Lâm Đồng',
            'name_en' => 'Lam Dong',
            'full_name' => 'Tỉnh Lâm Đồng',
            'full_name_en' => 'Lam Dong Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Lâm Đồng', 'Đắk Nông', 'Bình Thuận']
        ],
        [
            'id' => '28',
            'code' => 'DL',
            'name' => 'Đắk Lắk',
            'name_en' => 'Dak Lak',
            'full_name' => 'Tỉnh Đắk Lắk',
            'full_name_en' => 'Dak Lak Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Đắk Lắk', 'Phú Yên']
        ],
        [
            'id' => '29',
            'code' => 'DNA',
            'name' => 'Đồng Nai',
            'name_en' => 'Dong Nai',
            'full_name' => 'Tỉnh Đồng Nai',
            'full_name_en' => 'Dong Nai Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Đồng Nai', 'Bình Phước']
        ],
        [
            'id' => '30',
            'code' => 'TN2',
            'name' => 'Tây Ninh',
            'name_en' => 'Tay Ninh',
            'full_name' => 'Tỉnh Tây Ninh',
            'full_name_en' => 'Tay Ninh Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Tây Ninh', 'Long An']
        ],
        [
            'id' => '31',
            'code' => 'DT',
            'name' => 'Đồng Tháp',
            'name_en' => 'Dong Thap',
            'full_name' => 'Tỉnh Đồng Tháp',
            'full_name_en' => 'Dong Thap Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Đồng Tháp', 'Tiền Giang']
        ],
        [
            'id' => '32',
            'code' => 'VL',
            'name' => 'Vĩnh Long',
            'name_en' => 'Vinh Long',
            'full_name' => 'Tỉnh Vĩnh Long',
            'full_name_en' => 'Vinh Long Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Vĩnh Long', 'Bến Tre', 'Trà Vinh']
        ],
        [
            'id' => '33',
            'code' => 'CM',
            'name' => 'Cà Mau',
            'name_en' => 'Ca Mau',
            'full_name' => 'Tỉnh Cà Mau',
            'full_name_en' => 'Ca Mau Province',
            'type' => 'Tỉnh',
            'merged_from' => ['Cà Mau', 'Bạc Liêu']
        ],
        [
            'id' => '34',
            'code' => 'AG',
            'name' => 'An Giang',
            'name_en' => 'An Giang',
            'full_name' => 'Tỉnh An Giang',
            'full_name_en' => 'An Giang Province',
            'type' => 'Tỉnh',
            'merged_from' => ['An Giang', 'Kiên Giang']
        ],
    ]
];

