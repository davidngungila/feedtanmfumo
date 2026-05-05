<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TanzaniaLocationSeeder extends Seeder
{
    public function run(): void
    {
        // Tanzania Regions Data
        $regions = [
            ['region' => 'Arusha', 'regioncode' => 'AR'],
            ['region' => 'Dar es Salaam', 'regioncode' => 'DS'],
            ['region' => 'Dodoma', 'regioncode' => 'DO'],
            ['region' => 'Geita', 'regioncode' => 'GE'],
            ['region' => 'Iringa', 'regioncode' => 'IR'],
            ['region' => 'Kagera', 'regioncode' => 'KA'],
            ['region' => 'Katavi', 'regioncode' => 'KT'],
            ['region' => 'Kigoma', 'regioncode' => 'KI'],
            ['region' => 'Kilimanjaro', 'regioncode' => 'KJ'],
            ['region' => 'Lindi', 'regioncode' => 'LI'],
            ['region' => 'Manyara', 'regioncode' => 'MY'],
            ['region' => 'Mara', 'regioncode' => 'MR'],
            ['region' => 'Mbeya', 'regioncode' => 'MB'],
            ['region' => 'Morogoro', 'regioncode' => 'MO'],
            ['region' => 'Mtwara', 'regioncode' => 'MT'],
            ['region' => 'Mwanza', 'regioncode' => 'MW'],
            ['region' => 'Njombe', 'regioncode' => 'NJ'],
            ['region' => 'Rukwa', 'regioncode' => 'RK'],
            ['region' => 'Ruvuma', 'regioncode' => 'RV'],
            ['region' => 'Shinyanga', 'regioncode' => 'SH'],
            ['region' => 'Simiyu', 'regioncode' => 'SM'],
            ['region' => 'Singida', 'regioncode' => 'SG'],
            ['region' => 'Tabora', 'regioncode' => 'TB'],
            ['region' => 'Tanga', 'regioncode' => 'TG'],
        ];

        // Districts, Wards, Streets, and Places Data
        $locations = [
            // Arusha Region
            ['region' => 'Arusha', 'regioncode' => 'AR', 'district' => 'Arusha City', 'districtcode' => 'ARC', 'ward' => 'Sekei', 'wardcode' => 'SEK', 'street' => 'Njiro Road', 'places' => 'Arusha City Centre, Njiro, Sakina'],
            ['region' => 'Arusha', 'regioncode' => 'AR', 'district' => 'Arusha City', 'districtcode' => 'ARC', 'ward' => 'Elerai', 'wardcode' => 'ELR', 'street' => 'Moshi Road', 'places' => 'Elerai, Kijenge, Ngaramtoni'],
            ['region' => 'Arusha', 'regioncode' => 'AR', 'district' => 'Arusha Rural', 'districtcode' => 'ARR', 'ward' => 'Oldonyosambu', 'wardcode' => 'OLD', 'street' => 'Arusha-Moshi Highway', 'places' => 'Oldonyosambu, Engaresero, Olasiti'],
            ['region' => 'Arusha', 'regioncode' => 'AR', 'district' => 'Monduli', 'districtcode' => 'MON', 'ward' => 'Monduli', 'wardcode' => 'MON', 'street' => 'Monduli Road', 'places' => 'Monduli Town, Engaruka, Losimingori'],
            
            // Dar es Salaam Region
            ['region' => 'Dar es Salaam', 'regioncode' => 'DS', 'district' => 'Ilala', 'districtcode' => 'ILA', 'ward' => 'Kariakoo', 'wardcode' => 'KAR', 'street' => 'Kisutu Street', 'places' => 'Kariakoo Market, Posta, Swahili Street'],
            ['region' => 'Dar es Salaam', 'regioncode' => 'DS', 'district' => 'Ilala', 'districtcode' => 'ILA', 'ward' => 'Upanga', 'wardcode' => 'UPA', 'street' => 'Upanga Road', 'places' => 'Upanga West, Changombe, Vingunguti'],
            ['region' => 'Dar es Salaam', 'regioncode' => 'DS', 'district' => 'Kinondoni', 'districtcode' => 'KIN', 'ward' => 'Kawe', 'wardcode' => 'KAW', 'street' => 'Kawawa Road', 'places' => 'Kawe, Mwenge, Bonyokoni'],
            ['region' => 'Dar es Salaam', 'regioncode' => 'DS', 'district' => 'Kinondoni', 'districtcode' => 'KIN', 'ward' => 'Msasani', 'wardcode' => 'MSA', 'street' => 'Msasani Peninsula Road', 'places' => 'Masaki, Oyster Bay, Masaki'],
            ['region' => 'Dar es Salaam', 'regioncode' => 'DS', 'district' => 'Temeke', 'districtcode' => 'TEM', 'ward' => 'Temeke', 'wardcode' => 'TEM', 'street' => 'Temeke Road', 'places' => 'Temeke Town, Chamazi, Mbagala'],
            ['region' => 'Dar es Salaam', 'regioncode' => 'DS', 'district' => 'Ubungo', 'districtcode' => 'UBU', 'ward' => 'Ubungo', 'wardcode' => 'UBU', 'street' => 'Morogoro Road', 'places' => 'Ubungo Bus Terminal, Makumbusho, Kigogo'],
            
            // Dodoma Region
            ['region' => 'Dodoma', 'regioncode' => 'DO', 'district' => 'Dodoma City', 'districtcode' => 'DOC', 'ward' => 'Majengo', 'wardcode' => 'MAJ', 'street' => 'Nyerere Road', 'places' => 'Majengo, Maduka, Makole'],
            ['region' => 'Dodoma', 'regioncode' => 'DO', 'district' => 'Dodoma City', 'districtcode' => 'DOC', 'ward' => 'Njuweni', 'wardcode' => 'NJU', 'street' => 'Dodoma-Morogoro Road', 'places' => 'Njuweni, Hombolo, Mzakwe'],
            ['region' => 'Dodoma', 'regioncode' => 'DO', 'district' => 'Kondoa', 'districtcode' => 'KON', 'ward' => 'Kondoa', 'wardcode' => 'KON', 'street' => 'Kondoa Road', 'places' => 'Kondoa Town, Pahua, Bicha'],
            
            // Mwanza Region
            ['region' => 'Mwanza', 'regioncode' => 'MW', 'district' => 'Mwanza City', 'districtcode' => 'MWC', 'ward' => 'Nyamagana', 'wardcode' => 'NYA', 'street' => 'Nyamagana Road', 'places' => 'Nyamagana, Kirumba, Mwaloni'],
            ['region' => 'Mwanza', 'regioncode' => 'MW', 'district' => 'Mwanza City', 'districtcode' => 'MWC', 'ward' => 'Ilemela', 'wardcode' => 'ILE', 'street' => 'Ilemela Road', 'places' => 'Ilemela, Buswelu, Mabatini'],
            ['region' => 'Mwanza', 'regioncode' => 'MW', 'district' => 'Misungwi', 'districtcode' => 'MIS', 'ward' => 'Misungwi', 'wardcode' => 'MIS', 'street' => 'Misungwi Road', 'places' => 'Misungwi Town, Nyamikungu, Mwaluko'],
            
            // Mbeya Region
            ['region' => 'Mbeya', 'regioncode' => 'MB', 'district' => 'Mbeya City', 'districtcode' => 'MBC', 'ward' => 'Iyunga', 'wardcode' => 'IYU', 'street' => 'Mbeya Road', 'places' => 'Iyunga, Forest, Uyole'],
            ['region' => 'Mbeya', 'regioncode' => 'MB', 'district' => 'Mbeya City', 'districtcode' => 'MBC', 'ward' => 'Shinyanga', 'wardcode' => 'SHI', 'street' => 'Shinyanga Road', 'places' => 'Shinyanga, Mwenge, Mabala'],
            ['region' => 'Mbeya', 'regioncode' => 'MB', 'district' => 'Rungwe', 'districtcode' => 'RUN', 'ward' => 'Tukuyu', 'wardcode' => 'TUK', 'street' => 'Tukuyu Road', 'places' => 'Tukuyu Town, Kiwira, Kyimbila'],
            
            // Kilimanjaro Region
            ['region' => 'Kilimanjaro', 'regioncode' => 'KJ', 'district' => 'Moshi', 'districtcode' => 'MOS', 'ward' => 'Moshi Urban', 'wardcode' => 'MUR', 'street' => 'Moshi Road', 'places' => 'Moshi Town, Kibo, Himo'],
            ['region' => 'Kilimanjaro', 'regioncode' => 'KJ', 'district' => 'Moshi', 'districtcode' => 'MOS', 'ward' => 'Old Moshi', 'wardcode' => 'OLD', 'street' => 'Old Moshi Road', 'places' => 'Old Moshi, Machame, Marangu'],
            ['region' => 'Kilimanjaro', 'regioncode' => 'KJ', 'district' => 'Hai', 'districtcode' => 'HAI', 'ward' => 'Hai', 'wardcode' => 'HAI', 'street' => 'Hai Road', 'places' => 'Hai Town, Boma Ng\'ombe, West Kilimanjaro'],
            
            // Tanga Region
            ['region' => 'Tanga', 'regioncode' => 'TG', 'district' => 'Tanga City', 'districtcode' => 'TGC', 'ward' => 'Central', 'wardcode' => 'CEN', 'street' => 'Independence Avenue', 'places' => 'Tanga City Centre, Chumbageni, Mabawa'],
            ['region' => 'Tanga', 'regioncode' => 'TG', 'district' => 'Tanga City', 'districtcode' => 'TGC', 'ward' => 'Mabawa', 'wardcode' => 'MAB', 'street' => 'Mabawa Road', 'places' => 'Mabawa, Chongoleani, Kiomoni'],
            ['region' => 'Tanga', 'regioncode' => 'TG', 'district' => 'Muheza', 'districtcode' => 'MUH', 'ward' => 'Muheza', 'wardcode' => 'MUH', 'street' => 'Muheza Road', 'places' => 'Muheza Town, Amani, Magoroto'],
            
            // Morogoro Region
            ['region' => 'Morogoro', 'regioncode' => 'MO', 'district' => 'Morogoro Municipal', 'districtcode' => 'MOM', 'ward' => 'Mji Mkuu', 'wardcode' => 'MJM', 'street' => 'Mji Mkuu Street', 'places' => 'Morogoro Town, Kihonda, Bigwa'],
            ['region' => 'Morogoro', 'regioncode' => 'MO', 'district' => 'Morogoro Municipal', 'districtcode' => 'MOM', 'ward' => 'Boma', 'wardcode' => 'BOM', 'street' => 'Boma Road', 'places' => 'Boma, Tungi, Mazimbu'],
            ['region' => 'Morogoro', 'regioncode' => 'MO', 'district' => 'Mvomero', 'districtcode' => 'MVO', 'ward' => 'Mvomero', 'wardcode' => 'MVO', 'street' => 'Mvomero Road', 'places' => 'Mvomero Town, Mdandani, Mlali'],
            
            // Tabora Region
            ['region' => 'Tabora', 'regioncode' => 'TB', 'district' => 'Tabora Municipal', 'districtcode' => 'TBM', 'ward' => 'Njombe', 'wardcode' => 'NJO', 'street' => 'Njombe Street', 'places' => 'Tabora Town, Sikonge, Nzega'],
            ['region' => 'Tabora', 'regioncode' => 'TB', 'district' => 'Tabora Municipal', 'districtcode' => 'TBM', 'ward' => 'Kigoma', 'wardcode' => 'KIG', 'street' => 'Kigoma Street', 'places' => 'Kigoma, Urambo, Uyui'],
            
            // Iringa Region
            ['region' => 'Iringa', 'regioncode' => 'IR', 'district' => 'Iringa Municipal', 'districtcode' => 'IRM', 'ward' => 'Mkwawa', 'wardcode' => 'MKW', 'street' => 'Mkwawa Street', 'places' => 'Iringa Town, Ilula, Mafinga'],
            ['region' => 'Iringa', 'regioncode' => 'IR', 'district' => 'Iringa Rural', 'districtcode' => 'IRR', 'ward' => 'Mtwela', 'wardcode' => 'MTW', 'street' => 'Mtwela Road', 'places' => 'Mtwela, Kihesa, Nduli'],
            
            // Shinyanga Region
            ['region' => 'Shinyanga', 'regioncode' => 'SH', 'district' => 'Shinyanga Municipal', 'districtcode' => 'SHM', 'ward' => 'Shinyanga Urban', 'wardcode' => 'SHU', 'street' => 'Shinyanga Road', 'places' => 'Shinyanga Town, Kahama, Bukombe'],
            ['region' => 'Shinyanga', 'regioncode' => 'SH', 'district' => 'Kahama', 'districtcode' => 'KAH', 'ward' => 'Kahama', 'wardcode' => 'KAH', 'street' => 'Kahama Road', 'places' => 'Kahama Town, Mwadui, Ntungu'],
            
            // Singida Region
            ['region' => 'Singida', 'regioncode' => 'SG', 'district' => 'Singida Municipal', 'districtcode' => 'SGM', 'ward' => 'Singida Urban', 'wardcode' => 'SGU', 'street' => 'Singida Road', 'places' => 'Singida Town, Manyoni, Ikungi'],
            ['region' => 'Singida', 'regioncode' => 'SG', 'district' => 'Iramba', 'districtcode' => 'IRA', 'ward' => 'Iramba', 'wardcode' => 'IRA', 'street' => 'Iramba Road', 'places' => 'Iramba Town, Mkalama, Kisiriri'],
        ];

        // Insert data into database
        foreach ($locations as $location) {
            DB::table('tanzania_locations')->insert([
                'region' => $location['region'],
                'regioncode' => $location['regioncode'],
                'district' => $location['district'],
                'districtcode' => $location['districtcode'],
                'ward' => $location['ward'],
                'wardcode' => $location['wardcode'],
                'street' => $location['street'],
                'places' => $location['places'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Tanzania location data seeded successfully!');
        $this->command->info('Inserted ' . count($locations) . ' location records');
    }
}
