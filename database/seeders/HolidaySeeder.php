<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        // Load states indexed by normalized name
        $states = DB::table('states')
            ->select('id', 'name')
            ->get()
            ->mapWithKeys(fn ($s) => [
                $this->normalize($s->name) => $s->id
            ]);

        foreach ($this->holidays() as $holiday) {

            $stateKey = $this->normalize($holiday['state']);

            if (! $states->has($stateKey)) {
                $this->command?->warn("State not found: {$holiday['state']}");
                continue;
            }

            DB::table('holidays')->updateOrInsert(
                [
                    'date' => Carbon::createFromFormat('d/m/Y', $holiday['date'])->toDateString(),
                    'country_id' => 1,
                    'state_id' => $states[$stateKey],
                    'location_master_id' => null,
                ],
                [
                    'name' => $holiday['name'],
                    'is_optional' => false,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command?->info('✅ All 2026 holidays seeded successfully');
    }

    protected function normalize(string $value): string
    {
        return strtolower(trim($value));
    }

    /**
     * FULL HOLIDAY DATA — 2026
     */
    protected function holidays(): array
    {
        return [

            /* ===================== GUJARAT ===================== */
            ['name'=>'Makar Sankranti / Pongal / Magha Bihu','date'=>'14/01/2026','state'=>'gujarat'],
            ['name'=>'Republic Day','date'=>'26/01/2026','state'=>'gujarat'],
            ['name'=>'Dhuleti','date'=>'04/03/2026','state'=>'gujarat'],
            ['name'=>'Independence Day / Parsi New Year','date'=>'15/08/2026','state'=>'gujarat'],
            ['name'=>'Raksha Bandhan','date'=>'28/08/2026','state'=>'gujarat'],
            ['name'=>'Ganesh Chaturthi','date'=>'14/09/2026','state'=>'gujarat'],
            ['name'=>'Gandhi Jayanti','date'=>'02/10/2026','state'=>'gujarat'],
            ['name'=>'Durga Maha Navmi / Dussehra','date'=>'20/10/2026','state'=>'gujarat'],
            ['name'=>'Padtar Divas / Gap Day','date'=>'09/11/2026','state'=>'gujarat'],
            ['name'=>'Gujarati New Year / Govardhan Puja','date'=>'10/11/2026','state'=>'gujarat'],
            ['name'=>'Bhai Duj','date'=>'11/11/2026','state'=>'gujarat'],

            /* ===================== MAHARASHTRA ===================== */
            ['name'=>'Makar Sankranti / Pongal / Magha Bihu','date'=>'14/01/2026','state'=>'maharashtra'],
            ['name'=>'Republic Day','date'=>'26/01/2026','state'=>'maharashtra'],
            ['name'=>'Holi','date'=>'03/03/2026','state'=>'maharashtra'],
            ['name'=>'International Labour Day / Budha Purnima / May Day','date'=>'01/05/2026','state'=>'maharashtra'],
            ['name'=>'Independence Day / Parsi New Year','date'=>'15/08/2026','state'=>'maharashtra'],
            ['name'=>'Raksha Bandhan','date'=>'28/08/2026','state'=>'maharashtra'],
            ['name'=>'Ganesh Chaturthi','date'=>'14/09/2026','state'=>'maharashtra'],
            ['name'=>'Gandhi Jayanti','date'=>'02/10/2026','state'=>'maharashtra'],
            ['name'=>'Durga Maha Navmi / Dussehra','date'=>'20/10/2026','state'=>'maharashtra'],
            ['name'=>'Gujarati New Year / Govardhan Puja','date'=>'10/11/2026','state'=>'maharashtra'],
            ['name'=>'Bhai Duj','date'=>'11/11/2026','state'=>'maharashtra'],

            /* ===================== MADHYA PRADESH ===================== */
            ['name'=>'Makar Sankranti / Pongal / Magha Bihu','date'=>'14/01/2026','state'=>'madhya pradesh'],
            ['name'=>'Republic Day','date'=>'26/01/2026','state'=>'madhya pradesh'],
            ['name'=>'Holi','date'=>'03/03/2026','state'=>'madhya pradesh'],
            ['name'=>'International Labour Day / Budha Purnima / May Day','date'=>'01/05/2026','state'=>'madhya pradesh'],
            ['name'=>'Independence Day / Parsi New Year','date'=>'15/08/2026','state'=>'madhya pradesh'],
            ['name'=>'Raksha Bandhan','date'=>'28/08/2026','state'=>'madhya pradesh'],
            ['name'=>'Janmastami','date'=>'04/09/2026','state'=>'madhya pradesh'],
            ['name'=>'Gandhi Jayanti','date'=>'02/10/2026','state'=>'madhya pradesh'],
            ['name'=>'Durga Maha Navmi / Dussehra','date'=>'20/10/2026','state'=>'madhya pradesh'],
            ['name'=>'Gujarati New Year / Govardhan Puja','date'=>'10/11/2026','state'=>'madhya pradesh'],
            ['name'=>'Bhai Duj','date'=>'11/11/2026','state'=>'madhya pradesh'],

            /* ===================== DELHI ===================== */
            ['name'=>'Makar Sankranti / Pongal / Magha Bihu','date'=>'14/01/2026','state'=>'delhi'],
            ['name'=>'Republic Day','date'=>'26/01/2026','state'=>'delhi'],
            ['name'=>'Holi','date'=>'03/03/2026','state'=>'delhi'],
            ['name'=>'International Labour Day / May Day','date'=>'01/05/2026','state'=>'delhi'],
            ['name'=>'Independence Day / Parsi New Year','date'=>'15/08/2026','state'=>'delhi'],
            ['name'=>'Raksha Bandhan','date'=>'28/08/2026','state'=>'delhi'],
            ['name'=>'Janmastami','date'=>'04/09/2026','state'=>'delhi'],
            ['name'=>'Gandhi Jayanti','date'=>'02/10/2026','state'=>'delhi'],
            ['name'=>'Durga Maha Navmi / Dussehra','date'=>'20/10/2026','state'=>'delhi'],
            ['name'=>'Gujarati New Year / Govardhan Puja','date'=>'10/11/2026','state'=>'delhi'],
            ['name'=>'Bhai Duj','date'=>'11/11/2026','state'=>'delhi'],

            /* ===================== KARNATAKA ===================== */
            ['name'=>'Makar Sankranti / Pongal / Magha Bihu','date'=>'14/01/2026','state'=>'karnataka'],
            ['name'=>'Republic Day','date'=>'26/01/2026','state'=>'karnataka'],
            ['name'=>'Gudi Padwa / Ugadi / Chetchand','date'=>'19/03/2026','state'=>'karnataka'],
            ['name'=>'International Labour Day / May Day','date'=>'01/05/2026','state'=>'karnataka'],
            ['name'=>'Independence Day / Parsi New Year','date'=>'15/08/2026','state'=>'karnataka'],
            ['name'=>'Janmastami','date'=>'04/09/2026','state'=>'karnataka'],
            ['name'=>'Ganesh Chaturthi','date'=>'14/09/2026','state'=>'karnataka'],
            ['name'=>'Gandhi Jayanti','date'=>'02/10/2026','state'=>'karnataka'],
            ['name'=>'Durga Maha Navmi / Dussehra','date'=>'20/10/2026','state'=>'karnataka'],
            ['name'=>'Gujarati New Year / Govardhan Puja','date'=>'10/11/2026','state'=>'karnataka'],
            ['name'=>'Bhai Duj','date'=>'11/11/2026','state'=>'karnataka'],

            /* ===================== KERALA ===================== */
            ['name'=>'Makar Sankranti / Pongal / Magha Bihu','date'=>'14/01/2026','state'=>'kerala'],
            ['name'=>'Republic Day','date'=>'26/01/2026','state'=>'kerala'],
            ['name'=>'Ramadan Eid','date'=>'21/03/2026','state'=>'kerala'],
            ['name'=>'Good Friday','date'=>'03/04/2026','state'=>'kerala'],
            ['name'=>'Vishu / Tamil New Year','date'=>'14/04/2026','state'=>'kerala'],
            ['name'=>'International Labour Day / May Day','date'=>'01/05/2026','state'=>'kerala'],
            ['name'=>'Independence Day / Parsi New Year','date'=>'15/08/2026','state'=>'kerala'],
            ['name'=>'Onam','date'=>'26/08/2026','state'=>'kerala'],
            ['name'=>'Gandhi Jayanti','date'=>'02/10/2026','state'=>'kerala'],
            ['name'=>'Gujarati New Year / Govardhan Puja','date'=>'10/11/2026','state'=>'kerala'],
            ['name'=>'Christmas Day','date'=>'25/12/2026','state'=>'kerala'],

        ];
    }
}
