<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $damar = User::create([                                       // Dmaar
            'name' => 'damar ardian',
            'email' => 'damar.rizki212@gmail.com',
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            'password' => Hash::make('123456789'),
            'image' => 'damar.jpg'
        ]);

        $damar->assignRole('admin');

        $umar = User::create([                                        // Umar
            'name' => 'umar fakhry',
            'email' => 'umarfakhr1y@gmail.com',
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            'password' => Hash::make('123456789'),
            'image' => 'umar.jpg'
            
        ]);

        $umar->assignRole('admin');

        $baim = User::create([                                        // Baim
            'name' => 'ibraim ahmad',
            'email' => 'ibrahimahmad8896@gmail.com',
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            'password' => Hash::make('123456789'),
            'image' => 'baim.jpg'
        ]);

        $baim->assignRole('admin');

        $akmal = User::create([                                        // Trex
            'name' => 'akmal ilham',
            'email' => 'akmalilhamstp@gmail.com',
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            'password' => Hash::make('123456789'),
            'image' => 'akmal.jpg'
        ]);

        $akmal->assignRole('admin');

        $farhan = User::create([                                        // bombom
            'name' => 'farhan khosyi',
            'email' => 'farhankhosyi@gmail.com',
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            'password' => Hash::make('123456789'),
            'image' => 'Hands-Give.png'
        ]);

        $farhan->assignRole('admin');

        $padlu = User::create([                                         // bapak
            'name' => 'hanif fadhlu',
            'email' => 'hanifflu12@gmail.com',
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            'password' => Hash::make('123456789'),
            'image' => 'hanif_fadhlu.jpg'
        ]);

        $padlu->assignRole('admin');


        $bongga = User::create([                                        // bongga
            'name' => 'erlangga adithya',
            'email' => 'erlanggadithya97@gmail.com',
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            'password' => Hash::make('123456789'),
            'image' => 'erlangga.jpg'
        ]);

        $bongga->assignRole('admin');

        $ebed = User::create([                                          // ebed
            'name' => 'hasan munif',
            'email' => 'm.hasanmunif@gmail.com',
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            'password' => Hash::make('123456789'),
            'image' => 'munif.jpg'
        ]);

        $ebed->assignRole('admin');

        $kaufen = User::create([                                          // kaufen
            'name' => 'kaufen market',
            'email' => 'kaufen.market@gmail.com',
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            'password' => Hash::make('123456789'),
            'image' => 'kaufen_market.png'
        ]);

        $kaufen->assignRole('admin');

        $anggota = User::create([
            'name' => 'Anggota',
            'email' => 'anggota@role.test',
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            'password' => Hash::make('123456789'),
            'image' => 'Hands-Give.png'
        ]);

        $anggota->assignRole('anggota');

        $anggota2 = User::create([
            'name' => 'Member',
            'email' => 'anggota2@role.test',
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            'password' => Hash::make('123456789'),
            'image' => 'Hands-Give.png'
        ]);

        $anggota2->assignRole('anggota');

        $penjual = User::create([
            'name' => 'Penjual',
            'email' => 'penjual@role.test',
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            'password' => Hash::make('123456789'),
            'image' => 'Hands-Give.png'
        ]);

        $penjual->assignRole('penjual');

        $penjual2 = User::create([
            'name' => 'Seller',
            'email' => 'penjual2@role.test',
            "email_verified_at" => now()->timezone('Asia/Jakarta'),
            'password' => Hash::make('123456789'),
            'image' => 'Hands-Give.png'
        ]);

        $penjual2->assignRole('penjual');
    }
}
