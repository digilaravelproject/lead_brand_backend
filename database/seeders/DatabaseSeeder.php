<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Setup paths and directories
        $avatarDir = storage_path('app/public/profile_photos');
        $logoDir = storage_path('app/public/logos');

        if (!file_exists($avatarDir)) {
            mkdir($avatarDir, 0755, true);
        }
        if (!file_exists($logoDir)) {
            mkdir($logoDir, 0755, true);
        }

        $avatarPath = $avatarDir . '/test_avatar.jpg';
        $logoPath = $logoDir . '/test_logo.jpg';

        // 2. Generate dummy images using GD
        if (function_exists('imagecreatetruecolor')) {
            // Profile photo (indigo)
            $im = imagecreatetruecolor(150, 150);
            $bg = imagecolorallocate($im, 99, 102, 241); // indigo-500
            imagefill($im, 0, 0, $bg);
            $fg = imagecolorallocate($im, 255, 255, 255);
            imagestring($im, 3, 40, 65, "AWANTIKA", $fg);
            imagejpeg($im, $avatarPath);
            imagedestroy($im);

            // Business logo (yellow)
            $im = imagecreatetruecolor(80, 80);
            $bg = imagecolorallocate($im, 251, 191, 36); // amber-400
            imagefill($im, 0, 0, $bg);
            $fg = imagecolorallocate($im, 255, 255, 255);
            imagestring($im, 2, 20, 30, "LB LOGO", $fg);
            imagejpeg($im, $logoPath);
            imagedestroy($im);
        } else {
            // Fallback: write empty files if GD is not present
            file_put_contents($avatarPath, '');
            file_put_contents($logoPath, '');
        }

        // 3. Seed user with profile photo and logo paths
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'AWANTIKA',
                'password' => Hash::make('password'),
                'phone_number' => '9651017054',
                'destination' => 'AYODHYA',
                'profile_photo' => '/storage/profile_photos/test_avatar.jpg',
                'logo' => '/storage/logos/test_logo.jpg',
            ]
        );

        // 4. Seed default banner template
        Banner::updateOrCreate(
            ['title' => 'Default Servicing Consultancy Banner'],
            [
                'heading' => 'Doctors Save Lives, We Save Lifestyle',
                'services' => 'Premium Payment, Maturity Claim, Policy Revival, Policy Loan, Change in Address, Change in Nomination, Policy Branch Transfer, Change in Premium Mode',
                'image' => null,
                'status' => 1
            ]
        );
    }
}
