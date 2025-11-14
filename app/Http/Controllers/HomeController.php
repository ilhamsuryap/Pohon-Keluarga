<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function welcome()
    {
        return view('welcome', [
            'metaData' => $this->getMetaData()
        ]);
    }

    public function index()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        $metaData = [
            'title' => 'Pohon Keluarga - Platform Silsilah Keluarga Digital Terpercaya',
            'description' => 'Buat dan kelola pohon keluarga digital Anda dengan mudah. Platform terpercaya untuk menyimpan silsilah keluarga, riwayat keluarga, dan warisan leluhur untuk generasi mendatang.',
            'keywords' => 'pohon keluarga, silsilah keluarga, family tree, genealogi, riwayat keluarga, warisan leluhur, platform keluarga digital, indonesia',
            'author' => 'Pohon Keluarga',
            'robots' => 'index, follow',
            'canonical' => url('/'),
            'og_title' => 'Pohon Keluarga - Platform Silsilah Keluarga Digital',
            'og_description' => 'Platform terpercaya untuk membuat dan mengelola pohon keluarga digital. Simpan riwayat keluarga dan warisan leluhur untuk generasi mendatang.',
            'og_image' => asset('images/og-image.jpg'),
            'og_url' => url('/'),
        ];

        return view('welcome', compact('metaData'));
    }

    private function getMetaData()
    {
        return [
            'title' => 'Pohon Keluarga - Platform Silsilah Keluarga Digital Terpercaya',
            'description' => 'Buat dan kelola pohon keluarga digital Anda dengan mudah. Platform terpercaya untuk menyimpan silsilah keluarga, riwayat keluarga, dan warisan leluhur untuk generasi mendatang.',
            'keywords' => 'pohon keluarga, silsilah keluarga, family tree, genealogi, riwayat keluarga, warisan leluhur, platform keluarga digital, indonesia',
            'author' => 'Pohon Keluarga',
            'robots' => 'index, follow',
            'canonical' => url('/'),
            'og_title' => 'Pohon Keluarga - Platform Silsilah Keluarga Digital',
            'og_description' => 'Platform terpercaya untuk membuat dan mengelola pohon keluarga digital. Simpan riwayat keluarga dan warisan leluhur untuk generasi mendatang.',
            'og_image' => asset('images/og-image.jpg'),
            'og_url' => url('/'),
        ];
    }
}
