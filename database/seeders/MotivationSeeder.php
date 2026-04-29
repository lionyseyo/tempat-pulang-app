<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Motivation;

class MotivationSeeder extends Seeder
{
    public function run(): void
    {
        $messages = [

            // SEDIH
            ['mood' => 'sedih', 'message' => 'Tidak apa-apa merasa sedih hari ini. Perasaanmu valid dan kamu tidak harus selalu terlihat kuat di depan semua orang.'],
            ['mood' => 'sedih', 'message' => 'Air mata yang jatuh bukan tanda kelemahan, tapi bukti bahwa hatimu masih hidup dan peduli.'],
            ['mood' => 'sedih', 'message' => 'Hari ini mungkin terasa berat, tapi ini hanya satu bab kecil dari cerita panjang hidupmu.'],

            // MARAH
            ['mood' => 'marah', 'message' => 'Kamu boleh marah, tapi jangan biarkan kemarahan mengambil alih kendali atas dirimu.'],
            ['mood' => 'marah', 'message' => 'Tarik napas panjang. Kamu lebih kuat dari emosi yang sedang kamu rasakan.'],

            // CEMAS
            ['mood' => 'cemas', 'message' => 'Kecemasan membuat semuanya terasa lebih besar dari kenyataan. Kamu mampu melewati ini.'],
            ['mood' => 'cemas', 'message' => 'Fokus pada satu langkah kecil hari ini, itu sudah cukup.'],

            // LELAH
            ['mood' => 'lelah', 'message' => 'Tidak apa-apa jika hari ini kamu butuh istirahat. Kamu sudah berusaha sejauh ini.'],
            ['mood' => 'lelah', 'message' => 'Istirahat bukan berarti menyerah, itu bagian dari bertumbuh.'],

            // BAHAGIA
            ['mood' => 'bahagia', 'message' => 'Senyummu hari ini adalah kemenangan kecil yang patut dirayakan.'],
            ['mood' => 'bahagia', 'message' => 'Nikmati kebahagiaan sederhana ini, kamu pantas mendapatkannya.'],

            // BERSYUKUR
            ['mood' => 'bersyukur', 'message' => 'Bersyukur atas hal kecil hari ini akan membuka pintu kebahagiaan yang lebih besar.'],
            ['mood' => 'bersyukur', 'message' => 'Kamu dicintai lebih dari yang kamu sadari.'],

        ];

        foreach ($messages as $message) {
            Motivation::create($message);
        }
    }
}
